import { onMounted, ref } from 'vue';

export type Theme = 'light' | 'dark';

const STORAGE_KEY = 'aux-theme';
const theme = ref<Theme>('light');

function read(): Theme {
    try {
        const saved = localStorage.getItem(STORAGE_KEY);

        if (saved === 'light' || saved === 'dark') {
            return saved;
        }
    } catch {
        // Storage may be blocked.
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

/** Light/dark theme, following the system until the visitor chooses. */
export function useTheme() {
    onMounted(() => {
        theme.value = read();
    });

    function set(value: Theme): void {
        theme.value = value;
        document.documentElement.dataset.theme = value;

        try {
            localStorage.setItem(STORAGE_KEY, value);
        } catch {
            // Storage may be blocked.
        }
    }

    return { theme, set, toggle: () => set(theme.value === 'dark' ? 'light' : 'dark') };
}
