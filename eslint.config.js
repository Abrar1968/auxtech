import pluginVue from 'eslint-plugin-vue';
import tseslint from 'typescript-eslint';

export default tseslint.config(
    { ignores: ['vendor', 'node_modules', 'public', 'bootstrap/ssr', 'storage'] },
    ...tseslint.configs.recommended,
    ...pluginVue.configs['flat/recommended'],
    {
        files: ['**/*.vue'],
        languageOptions: { parserOptions: { parser: tseslint.parser } },
    },
    {
        rules: {
            'vue/multi-word-component-names': 'off',
            'vue/html-indent': 'off',
            'vue/max-attributes-per-line': 'off',
            'vue/singleline-html-element-content-newline': 'off',
            'vue/html-self-closing': 'off',
            // Optional TypeScript props are undefined by design.
            'vue/require-default-prop': 'off',
            // v-html only renders rich text that the server purifies on save (mews/purifier).
            'vue/no-v-html': 'off',
        },
    },
);
