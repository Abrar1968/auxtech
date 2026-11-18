/**
 * Renders the home page background video from scene.html.
 *
 *   node scripts/hero-video/render.cjs [frames-dir]
 *
 * Writes public/media/hero-loop.mp4 (H.264), hero-loop.webm (VP9) and
 * hero-poster.jpg. Needs Playwright's Chromium and ffmpeg with libx264/libvpx-vp9.
 */
const { chromium } = require('playwright');
const { execFileSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const os = require('os');

const FPS = 30;
const LOOP = 12;
const root = path.resolve(__dirname, '../..');
const out = path.join(root, 'public/media');
const frames = process.argv[2] || fs.mkdtempSync(path.join(os.tmpdir(), 'hero-frames-'));

(async () => {
    fs.mkdirSync(frames, { recursive: true });
    fs.mkdirSync(out, { recursive: true });

    const browser = await chromium.launch();
    const page = await browser.newPage({ viewport: { width: 1920, height: 1080 } });
    await page.goto('file://' + path.join(__dirname, 'scene.html'));
    await page.evaluate(() => document.fonts.ready);

    for (let i = 0; i < FPS * LOOP; i++) {
        await page.evaluate((t) => window.render(t), i / FPS);
        await page.screenshot({ path: path.join(frames, `f${String(i).padStart(4, '0')}.jpg`), type: 'jpeg', quality: 95 });
    }

    // Poster: a frame where the code is fully typed and a notification shows.
    await page.evaluate(() => window.render(10.5));
    await page.screenshot({ path: path.join(out, 'hero-poster.jpg'), type: 'jpeg', quality: 82 });
    await browser.close();

    const input = ['-y', '-framerate', String(FPS), '-i', path.join(frames, 'f%04d.jpg')];
    execFileSync('ffmpeg', [...input, '-c:v', 'libx264', '-preset', 'slow', '-crf', '27', '-pix_fmt', 'yuv420p',
        '-movflags', '+faststart', '-an', path.join(out, 'hero-loop.mp4')], { stdio: 'inherit' });
    execFileSync('ffmpeg', [...input, '-c:v', 'libvpx-vp9', '-b:v', '0', '-crf', '40', '-row-mt', '1',
        '-deadline', 'good', '-cpu-used', '2', '-pix_fmt', 'yuv420p', '-an', path.join(out, 'hero-loop.webm')], { stdio: 'inherit' });

    fs.rmSync(frames, { recursive: true, force: true });
    for (const f of ['hero-loop.mp4', 'hero-loop.webm', 'hero-poster.jpg']) {
        console.log(f, Math.round(fs.statSync(path.join(out, f)).size / 1024), 'kB');
    }
})();
