<script setup lang="ts">
import StarterKit from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { Bold, Heading2, Heading3, Italic, Link2, List, ListOrdered, Quote, Redo2, Undo2 } from '@lucide/vue';
import { onBeforeUnmount, watch } from 'vue';

/** Rich-text editor for admin fields. Output is sanitised again on the server. */
const model = defineModel<string | null>({ default: '' });
const props = defineProps<{ id?: string; invalid?: boolean }>();

const editor = useEditor({
    content: model.value ?? '',
    extensions: [StarterKit.configure({ heading: { levels: [2, 3] }, link: { openOnClick: false } })],
    editorProps: { attributes: { class: 'prose-aux min-h-48 px-4 py-3 outline-none', ...(props.id ? { id: props.id } : {}) } },
    onUpdate: ({ editor }) => {
        model.value = editor.isEmpty ? '' : editor.getHTML();
    },
});

watch(model, (value) => {
    if (editor.value && value !== editor.value.getHTML() && !(editor.value.isEmpty && !value)) {
        editor.value.commands.setContent(value ?? '', { emitUpdate: false });
    }
});

onBeforeUnmount(() => editor.value?.destroy());

function setLink(): void {
    const previous = editor.value?.getAttributes('link').href as string | undefined;
    const url = window.prompt('Link address', previous ?? 'https://');

    if (url === null) return;
    if (url === '') editor.value?.chain().focus().unsetLink().run();
    else editor.value?.chain().focus().setLink({ href: url }).run();
}

const tool = (active: boolean) => ['grid size-8 place-items-center rounded-md transition', active ? 'bg-brand-600 text-ink-950' : 'text-[#5c5f66] hover:bg-[#ececed]'];
</script>

<template>
    <div :class="['overflow-hidden rounded-lg border bg-white focus-within:border-focus focus-within:ring-3 focus-within:ring-brand-600/30', invalid ? 'border-red-400' : 'border-[#d9dadc]']">
        <div v-if="editor" class="flex flex-wrap gap-0.5 border-b border-[#e3e4e6] bg-[#f7f7f8] p-1">
            <button type="button" :class="tool(editor.isActive('heading', { level: 2 }))" title="Heading" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"><Heading2 :size="16" /></button>
            <button type="button" :class="tool(editor.isActive('heading', { level: 3 }))" title="Subheading" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"><Heading3 :size="16" /></button>
            <button type="button" :class="tool(editor.isActive('bold'))" title="Bold" @click="editor.chain().focus().toggleBold().run()"><Bold :size="16" /></button>
            <button type="button" :class="tool(editor.isActive('italic'))" title="Italic" @click="editor.chain().focus().toggleItalic().run()"><Italic :size="16" /></button>
            <button type="button" :class="tool(editor.isActive('bulletList'))" title="Bullet list" @click="editor.chain().focus().toggleBulletList().run()"><List :size="16" /></button>
            <button type="button" :class="tool(editor.isActive('orderedList'))" title="Numbered list" @click="editor.chain().focus().toggleOrderedList().run()"><ListOrdered :size="16" /></button>
            <button type="button" :class="tool(editor.isActive('blockquote'))" title="Quote" @click="editor.chain().focus().toggleBlockquote().run()"><Quote :size="16" /></button>
            <button type="button" :class="tool(editor.isActive('link'))" title="Link" @click="setLink"><Link2 :size="16" /></button>
            <span class="mx-1 w-px bg-[#e3e4e6]" />
            <button type="button" :class="tool(false)" title="Undo" @click="editor.chain().focus().undo().run()"><Undo2 :size="16" /></button>
            <button type="button" :class="tool(false)" title="Redo" @click="editor.chain().focus().redo().run()"><Redo2 :size="16" /></button>
        </div>
        <EditorContent :editor="editor" />
    </div>
</template>
