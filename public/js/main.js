document.addEventListener('DOMContentLoaded', function () {
    initLeadForms();
});

function initLeadForms() {
    const leadForms = document.querySelectorAll('.lead-form');

    if (!leadForms) {
        return;
    }

    leadForms.forEach(item => {
        
        initLeadForm(item);
    });

}

function initLeadForm(form){
    const submitButton = form.querySelector('.lead-form-submit') || form.querySelector('button[type="submit"]');
    const messageBox = form.querySelector('.lead-form-message');
    const formBlock = form.closest('[data-lead-form-block]') || form.parentElement;
    const statusModal = formBlock?.querySelector('[data-form-status-modal]') || null;
    const statusCard = statusModal?.querySelector('[data-form-status-card]') || null;
    const statusIcon = statusModal?.querySelector('[data-form-status-icon]') || null;
    const statusIconSymbol = statusModal?.querySelector('[data-form-status-icon-symbol]') || statusIcon?.firstElementChild || null;
    const statusTitle = statusModal?.querySelector('[data-form-status-title]') || null;
    const statusText = statusModal?.querySelector('[data-form-status-text]') || null;
    const statusAction = statusModal?.querySelector('[data-form-status-action]') || null;
    const csrfToken = form.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const modalStates = {
        success: {
            icon: '✓',
            title: 'Сообщение отправлено',
            text: 'Спасибо! Мы получили ваше сообщение и внимательно его изучим.',
            action: 'Отлично',
            overlayClass: 'bg-slate-950/60',
            toneClass: 'text-green-500',
            accentClass: 'bg-green-500/10',
            buttonClass: 'bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white',
        },
        error: {
            icon: '×',
            title: 'Ошибка отправки',
            text: 'Не удалось отправить форму. Проверьте обязательные поля и попробуйте ещё раз.',
            action: 'Повторить',
            overlayClass: 'bg-slate-950/60',
            toneClass: 'text-orange-500',
            accentClass: 'bg-orange-500/10',
            buttonClass: 'bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white',
        },
        info: {
            icon: 'i',
            title: 'Уведомление',
            text: 'Обрабатываем ваш запрос.',
            action: 'Понятно',
            overlayClass: 'bg-slate-950/60',
            toneClass: 'text-blue-600',
            accentClass: 'bg-blue-500/10',
            buttonClass: 'bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white',
        },
    };
    const resettableClasses = [
        'bg-slate-950/60',
        'bg-green-500/10',
        'bg-orange-500/10',
        'bg-blue-500/10',
        'text-green-500',
        'text-orange-500',
        'text-blue-600',
        'bg-orange-500',
        'hover:bg-orange-600',
        'active:bg-orange-700',
        'bg-blue-500',
        'hover:bg-blue-600',
        'active:bg-blue-700',
        'text-white',
    ];

    function hideModal() {
        if (!statusModal) {
            return;
        }

        statusModal.classList.add('hidden');
        statusModal.classList.remove('flex');
    }

    function showModal(type, text) {
        const state = modalStates[type] || modalStates.info;

        if (!statusModal || !statusCard || !statusIcon || !statusIconSymbol || !statusTitle || !statusText || !statusAction) {
            setMessage(text || state.text, type);
            return;
        }

        statusModal.classList.remove('hidden');
        statusModal.classList.add('flex');
        statusModal.dataset.state = type;
        statusModal.classList.remove(...resettableClasses);
        statusCard.classList.remove(...resettableClasses);
        statusIcon.classList.remove(...resettableClasses);
        statusAction.classList.remove(...resettableClasses);

        statusModal.classList.add(...state.overlayClass.split(' '));
        statusIcon.classList.add(...state.accentClass.split(' '), ...state.toneClass.split(' '));
        statusAction.classList.add(...state.buttonClass.split(' '));

        statusIconSymbol.textContent = state.icon;
        statusTitle.textContent = state.title;
        statusText.textContent = text || state.text;
        statusAction.textContent = state.action;
    }

    function setMessage(text, type) {
        if (!messageBox) {
            return;
        }

        messageBox.textContent = text;
        messageBox.classList.remove('hidden', 'text-red-400', 'text-green-400', 'text-gray-300');

        if (type === 'success') {
            messageBox.classList.add('text-green-400');
            return;
        }

        if (type === 'error') {
            messageBox.classList.add('text-red-400');
            return;
        }

        messageBox.classList.add('text-gray-300');
    }

    if (statusAction && statusAction.dataset.formStatusReady !== '1') {
        statusAction.addEventListener('click', function () {
            hideModal();
        });

        statusAction.dataset.formStatusReady = '1';
    }

    if (statusModal && statusModal.dataset.formStatusReady !== '1') {
        statusModal.addEventListener('click', function (event) {
            if (event.target === statusModal) {
                hideModal();
            }
        });

        statusModal.dataset.formStatusReady = '1';
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(form);
        const name_form = String(formData.get('name_form') || '').trim();
        const form_inputs = Array.from(formData.entries());
        // const phone = String(formData.get('email') || '').trim();
        // const body = String(formData.get('job') || '').trim();

        // console.log(Array.from(formData.entries()));

        if (!name_form) {
            showModal('error', 'Эта форма не работает');
            return;
        }

        const contentParts = [];
        //console.log(contentParts);
        let enter_filds = {
                    name_form: name_form,
                };

        form_inputs.forEach( item =>{  
            if(item[0]!='name_form'){
                if(item[0]!='block_id'){
                    contentParts.push(item[0] + ': ' + item[1]); 
                }                
                enter_filds[item[0]] = item[1];
            }          
        });
        enter_filds.content = contentParts.join('\n');
        //console.log(contentParts);

        

        if (submitButton) {
            submitButton.disabled = true;
        }

        hideModal();
        setMessage('Отправляем сообщение...', 'info');

        let errors_my='';        

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(enter_filds),
            });

            const data = await response.json();

            console.log(data);

            if (response.status === 422) {
                const errors = data.errors;
                //errors_my = errors.content[0];
                console.log(errors);
                errors_my=data.message;
            }

            

            if (!response.ok) {

                throw new Error('Lead request failed');
            }

            form.reset();
            setMessage('Сообщение отправлено. Спасибо!', 'success');
            showModal('success');
        } catch (errors) {            
            const errorText = 'Не удалось отправить сообщение. Попробуйте ещё раз.' + (errors_my ? ' ' + errors_my : '');
            setMessage(errorText, 'error');
            showModal('error', errorText);
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    });
}
