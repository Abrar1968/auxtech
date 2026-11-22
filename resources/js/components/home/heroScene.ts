/**
 * The home hero's background: light trails racing over a dark ground toward a
 * wireframe laptop and phone whose screens show real project screenshots.
 * Plain Canvas 2D (no 3D library), loaded after first paint by HomeHero.vue.
 *
 * The scene is a pure function of time and scroll progress: render(t, p).
 * Progress 0..1 is how far the visitor has scrolled through the pinned hero;
 * it moves the camera past the devices, stage by stage.
 */

type Vec = [number, number, number];
type Pt = { x: number; y: number; z: number };
export type Screen = { name: string; desktop: string | null; mobile: string | null };
export type Anchor = { x: number; y: number; visible: boolean };

export interface HeroScene {
    /** Draw one frame. t in seconds, progress 0..1. */
    render(t: number, progress: number): void;
    /** Match the canvas to its box; call on resize. */
    resize(): void;
    destroy(): void;
}

interface Options {
    screens: Screen[];
    /** Screen-space position of the device cluster, as fractions of the canvas. */
    focus: () => { x: number; y: number };
    /** Receives the projected hotspot anchors every frame (laptop screen, laptop base, phone, ground). */
    onAnchors?: (anchors: Anchor[]) => void;
    /** Fewer trails and a lower resolution, for phones. */
    lite?: boolean;
    /** Draw the scene in during the first seconds (lines ignite and trace themselves). */
    intro?: boolean;
}

const TAU = Math.PI * 2;
const YELLOW = '255,201,60';
const PALE = '255,232,161';
const SILVER = '217,218,220';
const WHITE = '255,255,255';
const AMBER = '240,178,26';

// Deterministic randomness: the scene looks the same on every visit.
function random(seed: number): () => number {
    let s = seed;
    return () => (s = (s * 16807) % 2147483647) / 2147483647;
}

/** A screenshot shrunk once to the size it is drawn at, so each frame copies few pixels. */
type Shot = { canvas: HTMLCanvasElement | null };

function loadImage(src: string | null, maxWidth: number): Shot | null {
    if (!src) return null;
    const shot: Shot = { canvas: null };
    const img = new Image();
    img.decoding = 'async';
    img.onload = () => {
        const scale = Math.min(1, maxWidth / img.naturalWidth);
        const c = document.createElement('canvas');
        c.width = Math.round(img.naturalWidth * scale);
        c.height = Math.round(img.naturalHeight * scale);
        c.getContext('2d')?.drawImage(img, 0, 0, c.width, c.height);
        shot.canvas = c;
    };
    img.src = src;
    return shot;
}

const ready = (shot: Shot | null): shot is Shot & { canvas: HTMLCanvasElement } => !!shot?.canvas;

export function createHeroScene(canvas: HTMLCanvasElement, options: Options): HeroScene {
    const ctx = canvas.getContext('2d')!;
    const lite = !!options.lite;
    const rnd = random(11);

    const trails = Array.from({ length: lite ? 11 : 18 }, (_, i) => {
        const side = i % 2 ? 1 : -1;
        const r = rnd();
        return {
            spread: side * (0.25 + r * 1.3),
            bend: (rnd() - 0.5) * 1.6,
            phase: rnd() * TAU,
            color: [YELLOW, PALE, SILVER, AMBER, WHITE, YELLOW, SILVER][i % 7],
            speed: 0.16 + rnd() * 0.22,
            offset: rnd(),
            width: 1 + rnd() * 1.3,
        };
    });

    const screens = options.screens
        .slice(0, 6)
        .map((s) => ({ desktop: loadImage(s.desktop, 560), mobile: loadImage(s.mobile, 220) }));

    let W = 0;
    let H = 0;
    let dpr = 1;

    function resize(): void {
        const box = canvas.getBoundingClientRect();
        // A soft, glowing background needs no retina pixels; 1x keeps the frame cheap.
        dpr = 1;
        W = Math.max(1, box.width);
        H = Math.max(1, box.height);
        canvas.width = Math.round(W * dpr);
        canvas.height = Math.round(H * dpr);
    }
    resize();

    function render(t: number, progress: number): void {
        const p = Math.min(1, Math.max(0, progress));
        // Intro: 0 → 1 over the first 1.8 s, eased; 1 when there is no intro.
        const reveal = options.intro ? 1 - Math.pow(1 - Math.min(1, t / 1.8), 3) : 1;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.clearRect(0, 0, W, H);

        // Camera: pinhole looking down +z, horizon a little above the middle.
        const f = Math.max(H, W * 0.62) * 0.95;
        const horizon = H * (0.4 - 0.05 * p);
        const camY = 1.8 + 0.6 * p;
        const focus = options.focus();
        // Place the devices where the layout wants them: depth from the focus height.
        const dz = Math.max(3.2, (camY * f) / Math.max(40, focus.y * H - horizon));
        const camZ = -dz + p * 7.5;
        const clusterZ = 0;
        // The laptop is about a third of the width on desktop and most of it on phones.
        const scaleCluster = Math.min(1, Math.min(W * (lite ? 0.52 : 0.27), 440) / ((3.2 * f) / dz));
        const camX = -((focus.x * W - W / 2) / f) * dz + p * 1.6;

        const project = (v: Vec): Pt | null => {
            const z = v[2] - camZ;
            if (z < 0.25) return null;
            const s = f / z;
            return { x: W / 2 + (v[0] - camX) * s, y: horizon + (camY - v[1]) * s, z };
        };

        // Ground: a faint perspective grid.
        ctx.globalCompositeOperation = 'source-over';
        ctx.lineWidth = 1;
        ctx.strokeStyle = `rgba(${WHITE},0.035)`;
        ctx.beginPath();
        for (let i = -6; i <= 6; i++) {
            const a = project([camX + i * 2.4, 0, camZ + 0.6]);
            const b = project([camX + i * 2.4, 0, camZ + 40]);
            if (!a || !b) continue;
            ctx.moveTo(a.x, a.y);
            ctx.lineTo(b.x, b.y);
        }
        ctx.stroke();
        for (let k = 1; k < 14; k++) {
            const z = camZ + ((k * 4 - ((t * 2.2) % 4)) % 56) + 0.6;
            const a = project([camX - 30, 0, z]);
            const b = project([camX + 30, 0, z]);
            if (!a || !b) continue;
            ctx.strokeStyle = `rgba(${WHITE},${0.045 * Math.min(1, 6 / (z - camZ))})`;
            ctx.beginPath();
            ctx.moveTo(a.x, a.y);
            ctx.lineTo(b.x, b.y);
            ctx.stroke();
        }

        // Light trails: lanes that fan out toward the viewer and narrow behind the devices.
        ctx.globalCompositeOperation = 'lighter';
        ctx.lineCap = 'round';
        const zNear = camZ + 0.5;
        const zFar = camZ + 64;
        const laneX = (tr: (typeof trails)[number], z: number): number => {
            const ahead = Math.max(0, clusterZ - z);
            return tr.spread * (1.9 + ahead * 0.85) + tr.bend * Math.sin(z * 0.09 + tr.phase + t * 0.2);
        };
        for (const tr of trails) {
            const pts: Pt[] = [];
            for (let i = 0; i <= 22; i++) {
                const u = i / 22;
                if (u < 1 - reveal) continue;
                const z = zNear + (zFar - zNear) * u * u;
                const q = project([laneX(tr, z), 0.02, z]);
                if (q) pts.push(q);
            }
            if (pts.length < 2) continue;
            const near = pts[0];
            const far = pts[pts.length - 1];
            const g = ctx.createLinearGradient(near.x, near.y, far.x, far.y);
            g.addColorStop(0, `rgba(${tr.color},0.8)`);
            g.addColorStop(0.6, `rgba(${tr.color},0.4)`);
            g.addColorStop(1, `rgba(${tr.color},0)`);
            ctx.strokeStyle = g;
            ctx.lineWidth = tr.width * 1.3;
            ctx.beginPath();
            ctx.moveTo(pts[0].x, pts[0].y);
            for (const q of pts) ctx.lineTo(q.x, q.y);
            ctx.stroke();
            // Bright heads racing toward the viewer; faster as the visitor scrolls.
            for (let k = 0; k < (reveal < 1 ? 0 : 2); k++) {
                const u = (t * tr.speed * (1 + p * 1.5) + tr.offset + k * 0.5) % 1;
                const z = zFar - (zFar - zNear) * Math.pow(u, 1.6);
                const head = project([laneX(tr, z), 0.02, z]);
                const tail = project([laneX(tr, z + 6), 0.02, z + 6]);
                if (!head || !tail) continue;
                const g = ctx.createLinearGradient(head.x, head.y, tail.x, tail.y);
                g.addColorStop(0, `rgba(${WHITE},0.95)`);
                g.addColorStop(0.25, `rgba(${tr.color},0.8)`);
                g.addColorStop(1, `rgba(${tr.color},0)`);
                ctx.strokeStyle = g;
                const width = Math.min(5, tr.width * 2 * (8 / head.z) + 0.8);
                // Glow around the head only: short, so it costs little.
                ctx.globalAlpha = 0.18;
                ctx.lineWidth = width * 4;
                ctx.beginPath();
                ctx.moveTo(head.x, head.y);
                ctx.lineTo(tail.x, tail.y);
                ctx.stroke();
                ctx.globalAlpha = 1;
                ctx.lineWidth = width;
                ctx.beginPath();
                ctx.moveTo(head.x, head.y);
                ctx.lineTo(tail.x, tail.y);
                ctx.stroke();
            }
        }

        // Devices: a laptop and a phone in glowing wireframe, turning as the visitor scrolls.
        const yaw = -0.42 + 0.06 * Math.sin((t / 12) * TAU) + p * 1.15;
        const k = scaleCluster;
        const bob = 0.06 * Math.sin((t / 6) * TAU);
        const world = (v: Vec, extraYaw = 0, pivot: Vec = [0, 0, 0]): Vec => {
            // Local rotation (phone), then cluster rotation, scale and placement.
            const y = v[1];
            let [x, , z] = v;
            if (extraYaw) {
                const c = Math.cos(extraYaw);
                const s = Math.sin(extraYaw);
                const lx = x - pivot[0];
                const lz = z - pivot[2];
                x = pivot[0] + lx * c - lz * s;
                z = pivot[2] + lx * s + lz * c;
            }
            const c = Math.cos(yaw);
            const s = Math.sin(yaw);
            return [(x * c - z * s) * k, (y + bob) * k, clusterZ + (x * s + z * c) * k];
        };

        const lean = (12 * Math.PI) / 180;
        const top = 0.12 + 2.0 * Math.cos(lean);
        const back = 1.05 + 2.0 * Math.sin(lean);
        const screen: Vec[] = [
            [-1.6, top, back],
            [1.6, top, back],
            [-1.6, 0.12, 1.05],
            [1.6, 0.12, 1.05],
        ];
        const inset = (q: Vec[], d: number): Vec[] => {
            const [tl, tr, bl, br] = q;
            const mix = (a: Vec, b: Vec, u: number): Vec => [
                a[0] + (b[0] - a[0]) * u,
                a[1] + (b[1] - a[1]) * u,
                a[2] + (b[2] - a[2]) * u,
            ];
            const u = d / 3.2;
            const v = d / 2.0;
            const tl2 = mix(mix(tl, tr, u), mix(bl, br, u), v);
            const tr2 = mix(mix(tl, tr, 1 - u), mix(bl, br, 1 - u), v);
            const bl2 = mix(mix(tl, tr, u), mix(bl, br, u), 1 - v);
            const br2 = mix(mix(tl, tr, 1 - u), mix(bl, br, 1 - u), 1 - v);
            return [tl2, tr2, bl2, br2];
        };
        const display = inset(screen, 0.09);

        const box = (x0: number, x1: number, y0: number, y1: number, z0: number, z1: number): [Vec, Vec][] => {
            const c = (x: number, y: number, z: number): Vec => [x, y, z];
            const e: [Vec, Vec][] = [];
            for (const y of [y0, y1]) {
                e.push(
                    [c(x0, y, z0), c(x1, y, z0)],
                    [c(x1, y, z0), c(x1, y, z1)],
                    [c(x1, y, z1), c(x0, y, z1)],
                    [c(x0, y, z1), c(x0, y, z0)],
                );
            }
            for (const [x, z] of [
                [x0, z0],
                [x1, z0],
                [x1, z1],
                [x0, z1],
            ])
                e.push([c(x, y0, z), c(x, y1, z)]);
            return e;
        };

        const laptopEdges: [Vec, Vec][] = [
            ...box(-1.6, 1.6, 0, 0.12, -1.05, 1.05),
            [screen[0], screen[1]],
            [screen[1], screen[3]],
            [screen[3], screen[2]],
            [screen[2], screen[0]],
        ];
        // Keyboard rows and trackpad.
        for (let r = 0; r < 4; r++)
            laptopEdges.push([
                [-1.3, 0.121, 0.75 - r * 0.22],
                [1.3, 0.121, 0.75 - r * 0.22],
            ]);
        laptopEdges.push(
            [
                [-0.45, 0.121, -0.2],
                [0.45, 0.121, -0.2],
            ],
            [
                [0.45, 0.121, -0.2],
                [0.45, 0.121, -0.85],
            ],
            [
                [0.45, 0.121, -0.85],
                [-0.45, 0.121, -0.85],
            ],
            [
                [-0.45, 0.121, -0.85],
                [-0.45, 0.121, -0.2],
            ],
        );

        const phonePivot: Vec = [2.45, 0, -0.35];
        const phoneYaw = -0.32;
        const phoneLean = (8 * Math.PI) / 180;
        const ph = (x: number, y: number, z: number): Vec => [
            phonePivot[0] + x,
            y * Math.cos(phoneLean),
            phonePivot[2] + z + y * Math.sin(phoneLean),
        ];
        const phoneFront: Vec[] = [ph(-0.47, 1.95, 0), ph(0.47, 1.95, 0), ph(-0.47, 0, 0), ph(0.47, 0, 0)];
        const phoneBack: Vec[] = [ph(-0.47, 1.95, 0.09), ph(0.47, 1.95, 0.09), ph(-0.47, 0, 0.09), ph(0.47, 0, 0.09)];
        const phoneDisplay: Vec[] = [
            ph(-0.41, 1.88, -0.001),
            ph(0.41, 1.88, -0.001),
            ph(-0.41, 0.07, -0.001),
            ph(0.41, 0.07, -0.001),
        ];
        const phoneEdges: [Vec, Vec][] = [];
        for (const q of [phoneFront, phoneBack])
            phoneEdges.push([q[0], q[1]], [q[1], q[3]], [q[3], q[2]], [q[2], q[0]]);
        for (let i = 0; i < 4; i++) phoneEdges.push([phoneFront[i], phoneBack[i]]);

        const P = (v: Vec, phone = false) => project(world(v, phone ? phoneYaw : 0, phonePivot));

        // Screenshots: cross-fade between featured projects every 4.5 s.
        const cycle = 4.5;
        const n = screens.length;
        const idx = n ? Math.floor(t / cycle) % n : 0;
        const next = n ? (idx + 1) % n : 0;
        const fade = Math.min(1, Math.max(0, (t % cycle) - (cycle - 0.8)) / 0.8);

        const paint = (corners: Vec[], phone: boolean, pick: 'desktop' | 'mobile') => {
            const q = corners.map((v) => P(v, phone));
            if (q.some((x) => !x)) return;
            const [tl, tr, bl, br] = q as Pt[];
            const base = ctx.globalAlpha;
            ctx.globalCompositeOperation = 'source-over';
            // Screen glass.
            ctx.fillStyle = 'rgba(20,21,24,0.92)';
            ctx.beginPath();
            ctx.moveTo(tl.x, tl.y);
            ctx.lineTo(tr.x, tr.y);
            ctx.lineTo(br.x, br.y);
            ctx.lineTo(bl.x, bl.y);
            ctx.closePath();
            ctx.fill();
            const layers: [Shot | null, number][] = n
                ? [
                      [screens[idx][pick] ?? screens[idx].desktop, 1 - fade],
                      [screens[next][pick] ?? screens[next].desktop, fade],
                  ]
                : [];
            ctx.save();
            ctx.clip();
            for (const [img, alpha] of layers) {
                if (!ready(img) || alpha <= 0) continue;
                // Cover-crop the screenshot from the top, then map it onto the screen (affine).
                const w = Math.hypot(tr.x - tl.x, tr.y - tl.y);
                const h = Math.hypot(bl.x - tl.x, bl.y - tl.y);
                const ratio = w / h;
                const src = img.canvas;
                let sw = src.width;
                let sh = sw / ratio;
                if (sh > src.height) {
                    sh = src.height;
                    sw = sh * ratio;
                }
                const sx = (src.width - sw) / 2;
                ctx.globalAlpha = base * alpha * 0.92;
                ctx.setTransform(
                    ((tr.x - tl.x) / sw) * dpr,
                    ((tr.y - tl.y) / sw) * dpr,
                    ((bl.x - tl.x) / sh) * dpr,
                    ((bl.y - tl.y) / sh) * dpr,
                    tl.x * dpr,
                    tl.y * dpr,
                );
                ctx.drawImage(src, sx, 0, sw, sh, 0, 0, sw, sh);
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            }
            ctx.globalAlpha = base;
            if (!layers.some(([img]) => ready(img))) {
                // Until a screenshot loads: a drawn interface.
                ctx.strokeStyle = `rgba(${SILVER},0.25)`;
                ctx.lineWidth = 1;
                for (let r = 1; r < 6; r++) {
                    const a = { x: tl.x + (bl.x - tl.x) * (r / 6), y: tl.y + (bl.y - tl.y) * (r / 6) };
                    const len = r % 2 ? 0.7 : 0.45;
                    ctx.beginPath();
                    ctx.moveTo(a.x + (tr.x - tl.x) * 0.08, a.y + (tr.y - tl.y) * 0.08);
                    ctx.lineTo(a.x + (tr.x - tl.x) * len, a.y + (tr.y - tl.y) * len);
                    ctx.stroke();
                }
            }
            ctx.restore();
            // Glass sheen.
            const g = ctx.createLinearGradient(tl.x, tl.y, br.x, br.y);
            g.addColorStop(0, 'rgba(255,255,255,0.10)');
            g.addColorStop(0.45, 'rgba(255,255,255,0)');
            ctx.fillStyle = g;
            ctx.beginPath();
            ctx.moveTo(tl.x, tl.y);
            ctx.lineTo(tr.x, tr.y);
            ctx.lineTo(br.x, br.y);
            ctx.lineTo(bl.x, bl.y);
            ctx.closePath();
            ctx.fill();
        };

        const strokeEdges = (edges: [Vec, Vec][], phone: boolean, color: string) => {
            ctx.globalCompositeOperation = 'lighter';
            for (const [width, alpha] of [
                [5, 0.07],
                [1.15, 0.85],
            ] as const) {
                ctx.strokeStyle = `rgba(${color},${alpha})`;
                ctx.lineWidth = width;
                ctx.beginPath();
                edges.forEach(([a, b], i) => {
                    // Staggered so the edges trace themselves in one after another.
                    const e = Math.min(1, Math.max(0, reveal * 1.6 - (i / edges.length) * 0.6));
                    if (e <= 0) return;
                    const pa = P(a, phone);
                    const pb = P(
                        e < 1 ? [a[0] + (b[0] - a[0]) * e, a[1] + (b[1] - a[1]) * e, a[2] + (b[2] - a[2]) * e] : b,
                        phone,
                    );
                    if (!pa || !pb) return;
                    ctx.moveTo(pa.x, pa.y);
                    ctx.lineTo(pb.x, pb.y);
                });
                ctx.stroke();
            }
        };

        // Soft yellow floor glow under the devices.
        const centre = P([0, 0, 0]);
        if (centre) {
            ctx.globalCompositeOperation = 'lighter';
            const r = Math.max(60, ((4.2 * f) / centre.z) * k);
            const g = ctx.createRadialGradient(centre.x, centre.y, 0, centre.x, centre.y, r);
            g.addColorStop(0, `rgba(${YELLOW},0.1)`);
            g.addColorStop(1, `rgba(${YELLOW},0)`);
            ctx.fillStyle = g;
            ctx.beginPath();
            ctx.ellipse(centre.x, centre.y, r, r * 0.35, 0, 0, TAU);
            ctx.fill();
        }

        ctx.globalAlpha = 1;
        if (reveal > 0.55) {
            const glass = Math.min(1, (reveal - 0.55) / 0.45);
            ctx.save();
            ctx.globalAlpha = glass;
            paint(display, false, 'desktop');
            paint(phoneDisplay, true, 'mobile');
            ctx.restore();
        }
        strokeEdges(laptopEdges, false, SILVER);
        strokeEdges(
            [
                [screen[0], screen[1]],
                [screen[1], screen[3]],
                [screen[3], screen[2]],
                [screen[2], screen[0]],
            ],
            false,
            YELLOW,
        );
        strokeEdges(phoneEdges, true, SILVER);

        // Hotspot anchors for the labels drawn in HTML.
        if (options.onAnchors) {
            const pts = [
                P(screen[0]),
                P([1.6, 0.12, -1.05]),
                P(ph(0, 1.95, 0), true),
                project([camX + (focus.x < 0.5 ? 3.2 : -3.2), 0, clusterZ - 2.2]),
            ];
            options.onAnchors(
                pts.map((q) =>
                    q && reveal >= 1
                        ? { x: q.x, y: q.y, visible: q.x > 8 && q.x < W - 8 && q.y > 8 && q.y < H - 8 }
                        : { x: 0, y: 0, visible: false },
                ),
            );
        }

        ctx.globalCompositeOperation = 'source-over';
    }

    return {
        render,
        resize,
        destroy() {
            screens.length = 0;
        },
    };
}
