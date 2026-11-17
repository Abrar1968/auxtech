import { readdirSync, readFileSync } from 'node:fs';
import { join } from 'node:path';
import { describe, expect, it } from 'vitest';
import bn from '../../../lang/bn.json';

/** Every interface string used on public pages must have a Bangla translation. */
function files(dir: string): string[] {
    return readdirSync(dir, { withFileTypes: true }).flatMap((entry) => {
        const path = join(dir, entry.name);

        return entry.isDirectory() ? files(path) : /\.(vue|ts)$/.test(entry.name) && !entry.name.endsWith('.test.ts') ? [path] : [];
    });
}

describe('Bangla translations', () => {
    it('cover every string passed to t() and tc() outside the admin panel', () => {
        const root = join(__dirname, '..');
        const sources = files(root).filter((file) => !/[/\\](Admin|Auth|admin)[/\\]|AdminLayout/.test(file));
        const keys = new Set<string>();

        for (const file of sources) {
            const source = readFileSync(file, 'utf8');

            for (const match of source.matchAll(/\bt\(\s*'((?:[^'\\]|\\.)*)'/g)) keys.add(match[1]);
            for (const match of source.matchAll(/\btc\(\s*'((?:[^'\\]|\\.)*)',\s*'((?:[^'\\]|\\.)*)'/g)) {
                keys.add(match[1]);
                keys.add(match[2]);
            }
        }

        const missing = [...keys].filter((key) => !(key in bn));

        expect(missing).toEqual([]);
    });
});
