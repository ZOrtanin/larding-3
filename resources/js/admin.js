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
    initAdminScrollRestore();
    initCodeEditor('block-content', 'larding:block-content-line-wrapping', 'blockContentCodeEditor');
    initCodeEditor('block-custom-css', 'larding:block-custom-css-line-wrapping', 'blockCssCodeEditor');
    initBlockEditorTabs();
});

function initAdminScrollRestore() {
    const storageKey = 'larding:admin-scroll:' + window.location.pathname + window.location.search;
    const savedScrollY = Number.parseInt(window.sessionStorage.getItem(storageKey) || '', 10);

    if (Number.isFinite(savedScrollY) && savedScrollY > 0) {
        window.requestAnimationFrame(function () {
            window.scrollTo({
                top: savedScrollY,
                left: 0,
                behavior: 'auto',
            });
        });
    }

    let saveTimeoutId = null;

    function persistScrollPosition() {
        window.sessionStorage.setItem(storageKey, String(Math.round(window.scrollY)));
    }

    window.addEventListener('scroll', function () {
        if (saveTimeoutId !== null) {
            window.clearTimeout(saveTimeoutId);
        }

        saveTimeoutId = window.setTimeout(persistScrollPosition, 80);
    }, { passive: true });

    window.addEventListener('beforeunload', persistScrollPosition);
}

function initCodeEditor(textareaId, storageKey, globalName) {
    const textarea = document.getElementById(textareaId);
    const lineWrappingToggle = document.getElementById('block-content-line-wrapping');

    if (!textarea || textarea.dataset.codeEditorReady === '1') {
        return;
    }

    const wrappingCompartment = new Compartment();
    const savedWrapping = window.localStorage.getItem(storageKey);
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

            window.localStorage.setItem(storageKey, enabled ? '1' : '0');
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
    window[globalName] = {
        sync: syncEditor,
    };
}

function initBlockEditorTabs() {
    const tabs = document.querySelectorAll('.block-editor-tab');
    const panes = document.querySelectorAll('.block-editor-pane');

    if (!tabs.length || !panes.length) {
        return;
    }

    function activateTab(tabName) {
        tabs.forEach(function (tab) {
            const isActive = tab.dataset.editorTab === tabName;
            tab.classList.toggle('bg-orange-500', isActive);
            tab.classList.toggle('text-white', isActive);
            tab.classList.toggle('text-gray-300', !isActive);
        });

        panes.forEach(function (pane) {
            pane.classList.toggle('hidden', pane.id !== 'block-editor-pane-' + tabName);
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activateTab(tab.dataset.editorTab || 'html');
        });
    });

    activateTab('html');
}
