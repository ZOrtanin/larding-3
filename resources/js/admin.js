import './bootstrap';
import '../../public/js/main.js';

import { EditorView } from '@codemirror/view';
import { Compartment, EditorState } from '@codemirror/state';
import { basicSetup } from 'codemirror';
import { html } from '@codemirror/lang-html';
import { oneDark } from '@codemirror/theme-one-dark';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

if (!window.__lardingAlpineStarted) {
    Alpine.start();
    window.__lardingAlpineStarted = true;
}

document.addEventListener('DOMContentLoaded', function () {
    initBlockContentEditor();
});

function initBlockContentEditor() {
    const textarea = document.getElementById('block-content');
    const lineWrappingToggle = document.getElementById('block-content-line-wrapping');

    if (!textarea || textarea.dataset.codeEditorReady === '1') {
        return;
    }

    const wrappingStorageKey = 'larding:block-content-line-wrapping';
    const wrappingCompartment = new Compartment();
    const savedWrapping = window.localStorage.getItem(wrappingStorageKey);
    const lineWrappingEnabled = savedWrapping === null ? true : savedWrapping === '1';

    const wrapper = document.createElement('div');
    wrapper.className = 'mt-2 overflow-hidden rounded-md border border-white/10 bg-[#0f172a]';

    textarea.classList.add('hidden');
    textarea.insertAdjacentElement('afterend', wrapper);

    const editor = new EditorView({
        state: EditorState.create({
            doc: textarea.value || '',
            extensions: [
                basicSetup,
                html(),
                oneDark,
                wrappingCompartment.of(lineWrappingEnabled ? EditorView.lineWrapping : []),
                EditorView.theme({
                    '&': {
                        fontSize: '13px',
                        minHeight: '360px',
                        backgroundColor: '#0f172a',
                    },
                    '.cm-scroller': {
                        fontFamily: 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace',
                    },
                    '.cm-content': {
                        padding: '16px',
                    },
                    '.cm-gutters': {
                        backgroundColor: '#111827',
                        color: '#94a3b8',
                        borderRight: '1px solid rgba(255,255,255,0.08)',
                    },
                    '.cm-activeLineGutter': {
                        backgroundColor: 'rgba(255,255,255,0.04)',
                    },
                    '.cm-activeLine': {
                        backgroundColor: 'rgba(255,255,255,0.04)',
                    },
                    '.cm-focused': {
                        outline: 'none',
                    },
                }),
                EditorView.updateListener.of((update) => {
                    if (!update.docChanged) {
                        return;
                    }

                    textarea.value = update.state.doc.toString();
                }),
            ],
        }),
        parent: wrapper,
    });

    if (lineWrappingToggle) {
        lineWrappingToggle.checked = lineWrappingEnabled;
        lineWrappingToggle.addEventListener('change', function () {
            const enabled = lineWrappingToggle.checked;

            editor.dispatch({
                effects: wrappingCompartment.reconfigure(enabled ? EditorView.lineWrapping : []),
            });

            window.localStorage.setItem(wrappingStorageKey, enabled ? '1' : '0');
        });
    }

    function syncEditor(value) {
        const nextValue = value ?? '';
        const currentValue = editor.state.doc.toString();

        if (currentValue === nextValue) {
            return;
        }

        editor.dispatch({
            changes: {
                from: 0,
                to: currentValue.length,
                insert: nextValue,
            },
        });
    }

    textarea.addEventListener('block-editor:update', function (event) {
        syncEditor(event.detail?.value ?? textarea.value);
    });

    textarea.dataset.codeEditorReady = '1';
    window.blockContentCodeEditor = {
        sync: syncEditor,
    };
}
