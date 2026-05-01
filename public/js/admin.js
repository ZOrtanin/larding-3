(function ($) {
    "use strict";

    // Календарь на dashboard.
    $('#calender').datetimepicker({
        inline: true,
        format: 'L'
    });
})(jQuery);


document.addEventListener('DOMContentLoaded', function () {
    initBlockEditor();
    initLeadTableActions();
    initVisitsChart();
    initUserPasswordGenerator();

    initDataModal({
        rowSelector: '.statistics-row',
        modalId: 'visit-modal',
        closeButtonId: 'visit-modal-close',
        dataFieldId: 'data-modal',
        labels: {
            id: 'ID',
            visitorId: 'Идентификатор посетителя',
            ip: 'IP',
            method: 'Метод',
            statusCode: 'Статус',
            userAgent: 'User Agent',
            browser: 'Браузер',
            platform: 'Платформа',
            deviceType: 'Тип устройства',
            url: 'URL',
            referer: 'Источник перехода',
            utmSource: 'UTM Source',
            utmMedium: 'UTM Medium',
            utmCampaign: 'UTM Campaign',
            utmContent: 'UTM Content',
            utmTerm: 'UTM Term',
            isMobile: 'Мобильное устройство',
            createdAt: 'Создано',
            updatedAt: 'Обновлено',
        },
    });

    initDataModal({
        rowSelector: '.lead-row',
        modalId: 'lead-modal',
        closeButtonId: 'lead-modal-close',
        dataFieldId: 'lead-data-modal',
        labels: {
            id: 'ID',
            name: 'Имя формы',
            content: 'Содержание',
            status: 'Статус заявки',
            comments: 'Комментарий',
            createdAt: 'Создано',
            updatedAt: 'Обновлено',
        },
        onOpen: function (row) {
            const statusForm = document.getElementById('lead-status-form');
            const statusSelect = document.getElementById('lead-status-select');
            const commentInput = document.getElementById('lead-comment-input');

            if (!statusForm || !statusSelect) {
                return;
            }

            statusForm.action = row.dataset.statusUrl || '';
            statusSelect.value = row.dataset.status || 'new';

            if (commentInput) {
                commentInput.value = '';
            }
        },
    });

    initDataModal({
        rowSelector: '.file-row',
        modalId: 'file-modal',
        closeButtonId: 'file-modal-close',
        dataFieldId: 'file-data-modal',
        labels: {
            id: 'ID',
            type: 'Тип',
            name: 'Имя',
            path: 'Путь',
            size: 'Размер',
            description: 'Описание',
            createdAt: 'Создан',
        },
    });

    initDataModal({
        rowSelector: '.user-row',
        modalId: 'user-modal',
        closeButtonId: 'user-modal-close',
        dataFieldId: 'user-data-modal',
        labels: {
            id: 'ID',
            name: 'Имя',
            email: 'Email',
            role: 'Роль',
            emailVerified: 'Статус email',
            createdAt: 'Создан',
        },
        onOpen: function (row) {
            const roleForm = document.getElementById('user-role-form');
            const roleSelect = document.getElementById('user-role-select');
            const roleSection = document.getElementById('user-role-section');
            const passwordForm = document.getElementById('user-password-form');
            const passwordSection = document.getElementById('user-password-section');
            const passwordInput = document.getElementById('user-password-input');
            const protectedNote = document.getElementById('user-role-protected-note');
            const isProtected = row.dataset.roleProtected === '1';

            if (roleForm) {
                roleForm.action = row.dataset.roleUrl || '';
            }

            if (roleSelect) {
                roleSelect.value = row.dataset.roleId || '';
            }

            if (roleSection) {
                roleSection.classList.toggle('hidden', isProtected);
            }

            if (passwordForm) {
                passwordForm.action = row.dataset.passwordUrl || '';
            }

            if (passwordSection) {
                passwordSection.classList.toggle('hidden', isProtected);
            }

            if (passwordInput) {
                passwordInput.value = '';
            }

            if (protectedNote) {
                protectedNote.classList.toggle('hidden', !isProtected);
            }
        },
    });
});

function initUserPasswordGenerator() {
    const generateButton = document.getElementById('user-password-generate');
    const passwordInput = document.getElementById('user-password-input');

    if (!generateButton || !passwordInput) {
        return;
    }

    generateButton.addEventListener('click', function () {
        const alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*';
        const passwordLength = 14;
        let password = '';

        for (let index = 0; index < passwordLength; index += 1) {
            const randomIndex = Math.floor(Math.random() * alphabet.length);
            password += alphabet[randomIndex];
        }

        passwordInput.value = password;
        passwordInput.focus();
        passwordInput.select();
    });
}

function initVisitsChart() {
    const chartElement = document.getElementById('visits-chart');

    if (!chartElement || typeof Chart === 'undefined') {
        return;
    }

    const labels = JSON.parse(chartElement.dataset.labels || '[]');
    const values = JSON.parse(chartElement.dataset.values || '[]');
    const context = chartElement.getContext('2d');

    if (!context) {
        return;
    }

    const gradient = context.createLinearGradient(0, 0, 0, chartElement.height || 260);
    gradient.addColorStop(0, 'rgba(249, 115, 22, 0.35)');
    gradient.addColorStop(1, 'rgba(249, 115, 22, 0.02)');

    new Chart(context, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Посещения',
                data: values,
                borderColor: '#f97316',
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 4,
                pointHoverRadius: 5,
                pointBackgroundColor: '#fb923c',
                pointBorderColor: '#1f2937',
                pointBorderWidth: 2,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                x: {
                    ticks: {
                        color: '#9ca3af',
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.06)',
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#9ca3af',
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.06)',
                    },
                },
            },
        },
    });
}

function initLeadTableActions() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    async function deleteLead(row) {
        const deleteUrl = row?.dataset?.deleteUrl;

        if (!deleteUrl || !window.confirm('Удалить эту заявку?')) {
            return;
        }

        const response = await fetch(deleteUrl, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        if (!response.ok) {
            return;
        }

        row.remove();
    }

    document.addEventListener('click', async function (event) {
        const deleteButton = event.target.closest('.js-delete-lead');

        if (!deleteButton) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const row = deleteButton.closest('.lead-row');
        await deleteLead(row);
    });
}

function initBlockEditor() {
    const blockForm = document.getElementById('block-form');
    const drawer = document.getElementById('drawer');
    const drawerPanel = document.getElementById('block-editor-panel');
    const resizeHandle = document.getElementById('block-editor-resize-handle');
    const drawerTitle = document.getElementById('drawer-title');
    const drawerCloseButton = document.getElementById('drawer-close-button');
    const submitButton = document.getElementById('block-submit-button');
    const deleteDrawerButton = document.getElementById('block-delete-button');
    const templateSelect = document.getElementById('block-template-select');
    const templateApplyButton = document.getElementById('block-template-apply');
    const methodInput = document.getElementById('block-form-method');
    const nameInput = document.getElementById('block-name');
    const descriptionInput = document.getElementById('block-description');
    const contentInput = document.getElementById('block-content');
    const variablesList = document.getElementById('block-variables-list');
    const variableTemplate = document.getElementById('block-variable-template');
    const addVariableButton = document.getElementById('block-variable-add');
    const addBlockButtons = document.querySelectorAll('.js-add-block-button');

    if (!blockForm || !drawer || !drawerTitle || !submitButton || !methodInput || !nameInput || !descriptionInput || !contentInput) {
        return;
    }

    initBlockEditorResize(drawerPanel, resizeHandle);

    function openDrawer() {
        if (typeof drawer.showModal === 'function' && !drawer.open) {
            drawer.showModal();
        }
    }

    function closeDrawer() {
        if (typeof drawer.close === 'function' && drawer.open) {
            drawer.close();
        }
    }

    const createAction = document.getElementById('url_block_create').value;
    const showActionTemplate = document.getElementById('url_block_show').value;
    const templateListUrl = document.getElementById('url_block_templates').value;
    const templateShowUrlTemplate = document.getElementById('url_block_template_show').value;
    const deleteActionTemplate = document.getElementById('url_block_delete').value;
    const upActionTemplate = document.getElementById('url_block_up').value;
    const downActionTemplate = document.getElementById('url_block_down').value;
    const visibilityActionTemplate = document.getElementById('url_block_visibility').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let selectedBlockId = null;

    function buildUrl(routeTemplate, placeholder, value) {
        return routeTemplate.replace(/\\\//g, '/').replace(placeholder, String(value));
    }

    async function request(url, options = {}) {
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            ...(options.headers || {}),
        };

        return fetch(url, {
            credentials: 'same-origin',
            ...options,
            headers,
        });
    }

    async function loadJson(url) {
        const response = await request(url, { method: 'GET' });

        if (!response.ok) {
            return null;
        }

        return response.json();
    }

    function reindexVariableInputs() {
        if (!variablesList) {
            return;
        }

        Array.from(variablesList.querySelectorAll('.block-variable-item')).forEach(function (item, index) {
            const fields = {
                name: item.querySelector('[data-field="name"]'),
                label: item.querySelector('[data-field="label"]'),
                type: item.querySelector('[data-field="type"]'),
                default_value: item.querySelector('[data-field="default_value"]'),
                required: item.querySelector('[data-field="required"]'),
            };

            Object.entries(fields).forEach(function ([fieldName, field]) {
                if (!field) {
                    return;
                }

                field.name = 'variables[' + index + '][' + fieldName + ']';

                if (fieldName === 'required') {
                    field.value = '1';
                }
            });
        });
    }

    function clearVariableList() {
        if (!variablesList) {
            return;
        }

        variablesList.innerHTML = '';
    }

    function appendVariable(variable) {
        if (!variablesList || !variableTemplate) {
            return;
        }

        const fragment = variableTemplate.content.cloneNode(true);
        const item = fragment.querySelector('.block-variable-item');

        if (!item) {
            return;
        }

        const nameField = item.querySelector('[data-field="name"]');
        const labelField = item.querySelector('[data-field="label"]');
        const typeField = item.querySelector('[data-field="type"]');
        const defaultValueField = item.querySelector('[data-field="default_value"]');
        const requiredField = item.querySelector('[data-field="required"]');

        if (nameField) {
            nameField.value = variable?.name ?? '';
        }

        if (labelField) {
            labelField.value = variable?.label ?? '';
        }

        if (typeField) {
            typeField.value = variable?.type ?? 'text';
        }

        if (defaultValueField) {
            defaultValueField.value = variable?.default_value ?? '';
        }

        if (requiredField) {
            requiredField.checked = Boolean(variable?.required);
        }

        variablesList.appendChild(fragment);
        reindexVariableInputs();
    }

    function fillVariables(variables) {
        clearVariableList();

        (variables || []).forEach(function (variable) {
            appendVariable(variable);
        });
    }

    function fillBlockForm(block) {
        nameInput.value = block.name ?? '';
        descriptionInput.value = block.description ?? '';
        contentInput.value = block.content ?? '';
        contentInput.dispatchEvent(new CustomEvent('block-editor:update', {
            detail: { value: contentInput.value },
        }));
        fillVariables(block.variables ?? []);
    }

    function setDrawerMode(options) {
        drawerTitle.textContent = options.title;
        submitButton.textContent = options.submitText;
        blockForm.action = options.action;
        methodInput.value = 'PATCH';
        selectedBlockId = options.blockId ?? null;

        fillBlockForm(options.block ?? {});

        if (deleteDrawerButton) {
            deleteDrawerButton.classList.toggle('hidden', !options.blockId);
        }
    }

    function setCreateMode() {
        setDrawerMode({
            title: 'Добавить блок',
            submitText: '+ Добавить блок',
            action: createAction,
            block: {
                name: '',
                description: '',
                content: '',
                variables: [],
            },
        });

        if (templateSelect) {
            templateSelect.value = '';
        }

        openDrawer();
    }

    async function setEditMode(blockId) {
        if (!blockId) {
            return;
        }

        const block = await loadJson(buildUrl(showActionTemplate, '__BLOCK__', blockId));
        if (!block || !block.id) {
            return;
        }

        setDrawerMode({
            title: 'Редактировать блок',
            submitText: 'Сохранить изменения',
            action: buildUrl(showActionTemplate, '__BLOCK__', block.id),
            blockId: String(block.id),
            block,
        });

        openDrawer();
    }

    async function runBlockAction(routeTemplate, blockId, method) {
        if (!blockId) {
            return;
        }

        const response = await request(buildUrl(routeTemplate, '__BLOCK__', blockId), { method });
        if (!response.ok) {
            return;
        }

        location.reload();
    }

    async function deleteBlock(blockId) {
        if (!blockId || !window.confirm('Удалить этот блок?')) {
            return;
        }

        await runBlockAction(deleteActionTemplate, blockId, 'DELETE');
    }

    async function toggleVisibility(blockId) {
        await runBlockAction(visibilityActionTemplate, blockId, 'PATCH');
    }

    async function applyTemplate(templateId) {
        if (!templateId) {
            return;
        }

        const template = await loadJson(buildUrl(templateShowUrlTemplate, '__TEMPLATE__', templateId));
        if (!template || !template.id) {
            return;
        }

        fillBlockForm(template);
    }

    async function loadTemplates() {
        if (!templateSelect) {
            return;
        }

        const templates = await loadJson(templateListUrl);
        if (!Array.isArray(templates)) {
            return;
        }

        templateSelect.innerHTML = '<option value="">Выберите шаблон</option>';
        templates.forEach(function (template) {
            const option = document.createElement('option');
            option.value = String(template.id);
            option.textContent = template.name;
            templateSelect.appendChild(option);
        });
    }

    addBlockButtons.forEach(function (button) {
        button.addEventListener('click', setCreateMode);
    });

    if (templateApplyButton) {
        templateApplyButton.addEventListener('click', async function () {
            if (!templateSelect) {
                return;
            }

            await applyTemplate(templateSelect.value);
        });
    }

    if (deleteDrawerButton) {
        deleteDrawerButton.addEventListener('click', async function () {
            await deleteBlock(selectedBlockId);
        });
    }

    if (drawerCloseButton) {
        drawerCloseButton.addEventListener('click', closeDrawer);
    }

    if (addVariableButton) {
        addVariableButton.addEventListener('click', function () {
            appendVariable({
                type: 'text',
                required: false,
            });
        });
    }

    document.addEventListener('click', async function (event) {
        const removeVariableButton = event.target.closest('[data-action="remove-variable"]');
        if (removeVariableButton) {
            const variableItem = removeVariableButton.closest('.block-variable-item');
            if (variableItem) {
                variableItem.remove();
                reindexVariableInputs();
            }

            return;
        }

        const upButton = event.target.closest('.btn-up');
        if (upButton) {
            await runBlockAction(upActionTemplate, upButton.dataset.blockId, 'PATCH');
            return;
        }

        const downButton = event.target.closest('.btn-down');
        if (downButton) {
            await runBlockAction(downActionTemplate, downButton.dataset.blockId, 'PATCH');
            return;
        }

        const viewButton = event.target.closest('.btn-view');
        if (viewButton) {
            await toggleVisibility(viewButton.dataset.blockId);
            return;
        }

        const editButton = event.target.closest('.js-edit-block-button');
        if (!editButton) {
            return;
        }

        await setEditMode(editButton.dataset.blockId);
    });

    document.addEventListener('mousemove', function (event) {
        const block = event.target.closest('.js-block-edit');
        const controls = document.querySelectorAll('.block-settings');

        controls.forEach(function (item) {
            item.classList.add('hidden');
        });

        if (!block) {
            return;
        }

        const blockControls = block.querySelector('.block-settings');
        if (blockControls) {
            blockControls.classList.remove('hidden');
        }
    });

    loadTemplates();
}

function initBlockEditorResize(drawerPanel, resizeHandle) {
    if (!drawerPanel || !resizeHandle) {
        return;
    }

    const storageKey = 'larding:block-editor-width';
    const minWidth = 420;
    const defaultWidth = 448;
    const maxWidthOffset = 96;
    let isResizing = false;
    let activePointerId = null;

    function maxWidth() {
        return Math.max(minWidth, window.innerWidth - maxWidthOffset);
    }

    function clampWidth(width) {
        return Math.min(Math.max(width, minWidth), maxWidth());
    }

    function applyWidth(width) {
        const normalizedWidth = clampWidth(width);
        drawerPanel.style.maxWidth = 'none';
        drawerPanel.style.width = normalizedWidth + 'px';
    }

    const storedWidth = Number.parseInt(window.localStorage.getItem(storageKey) || '', 10);
    applyWidth(Number.isFinite(storedWidth) ? storedWidth : defaultWidth);

    function startResize(event) {
        if (event.button !== undefined && event.button !== 0) {
            return;
        }

        isResizing = true;
        activePointerId = event.pointerId ?? null;
        document.body.style.userSelect = 'none';
        document.body.style.cursor = 'col-resize';
        event.preventDefault();

        if (activePointerId !== null && typeof resizeHandle.setPointerCapture === 'function') {
            resizeHandle.setPointerCapture(activePointerId);
        }
    }

    resizeHandle.addEventListener('pointerdown', startResize);

    document.addEventListener('pointermove', function (event) {
        if (!isResizing || (activePointerId !== null && event.pointerId !== activePointerId)) {
            return;
        }

        const width = window.innerWidth - event.clientX;
        applyWidth(width);
    });

    function stopResize(event) {
        if (!isResizing) {
            return;
        }

        if (activePointerId !== null && event?.pointerId !== undefined && event.pointerId !== activePointerId) {
            return;
        }

        isResizing = false;
        if (activePointerId !== null && typeof resizeHandle.releasePointerCapture === 'function') {
            try {
                resizeHandle.releasePointerCapture(activePointerId);
            } catch (error) {
                // ignore missing capture state
            }
        }
        activePointerId = null;
        document.body.style.userSelect = '';
        document.body.style.cursor = '';
        window.localStorage.setItem(storageKey, String(Math.round(drawerPanel.getBoundingClientRect().width)));
    }

    document.addEventListener('pointerup', stopResize);
    document.addEventListener('pointercancel', stopResize);

    window.addEventListener('resize', function () {
        const currentWidth = drawerPanel.getBoundingClientRect().width || defaultWidth;
        applyWidth(currentWidth);
    });
}

function initDataModal(config) {
    const rows = document.querySelectorAll(config.rowSelector);
    const modal = document.getElementById(config.modalId);
    const closeButton = document.getElementById(config.closeButtonId);
    const dataField = document.getElementById(config.dataFieldId);

    if (!rows.length || !modal || !closeButton || !dataField) {
        return;
    }

    function closeModal() {
        modal.classList.add('hidden');
        dataField.innerHTML = '';
    }

    // Добавляем одно поле в тело модального окна.
    function appendField(label, value) {
        const wrapper = document.createElement('div');
        const title = document.createElement('p');
        const content = document.createElement('p');

        title.className = 'text-xs uppercase tracking-wide text-gray-400';
        title.textContent = label;

        content.className = 'mt-1 break-all text-sm text-white';

        if (label === 'Комментарий' && value && value !== '-') {
            wrapper.className = 'md:col-span-2';
            content.className = 'mt-1 overflow-y-auto rounded-md border border-white/10 bg-white/5 p-3 text-sm text-white';
            content.style.maxHeight = '100px';
            content.innerHTML = value
                .split(/\n{2,}/)
                .map(function (entry) {
                    return entry.replace(/\n/g, '<br>');
                })
                .join('<br><br>');

            requestAnimationFrame(function () {
                content.scrollTop = content.scrollHeight;
            });
        } else {
            content.textContent = value || '-';
        }

        wrapper.appendChild(title);
        wrapper.appendChild(content);
        dataField.appendChild(wrapper);
    }

    // При открытии пересобираем модалку из data-атрибутов строки.
    function openModal(row) {
        dataField.innerHTML = '';

        Object.entries(row.dataset).forEach(function ([key, value]) {
            if (key === 'statusUrl' || key === 'deleteUrl' || key === 'roleUrl' || key === 'roleId' || key === 'roleProtected' || key === 'passwordUrl') {
                return;
            }

            appendField(config.labels[key] || key, value);
        });

        if (typeof config.onOpen === 'function') {
            config.onOpen(row);
        }

        modal.classList.remove('hidden');
    }

    // Открытие модалки по клику на строку таблицы.
    rows.forEach(function (row) {
        row.addEventListener('click', function (event) {
            if (event.target.closest('.js-no-modal')) {
                return;
            }

            openModal(row);
        });
    });

    closeButton.addEventListener('click', closeModal);

    // Закрытие по клику на затемнение.
    modal.addEventListener('click', function (event) {
        if (event.target === modal || event.target === modal.firstElementChild) {
            closeModal();
        }
    });

    // Закрытие по клавише Escape.
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
}
