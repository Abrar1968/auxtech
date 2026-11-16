/// <reference types="vite/client" />

declare module '*.vue' {
    import type { DefineComponent } from 'vue';
    const component: DefineComponent<object, object, unknown>;
    export default component;
}

declare module 'ziggy-js' {
    import type { Plugin } from 'vue';
    export const ZiggyVue: Plugin;
    export function route(name?: string, params?: unknown, absolute?: boolean): string;
}

interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
