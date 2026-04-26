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
    
    const submitButton = form.querySelector('.lead-form-submit');
    const messageBox = form.querySelector('.lead-form-message');
    const csrfToken = form.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

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

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(form);
        const name_form = String(formData.get('name_form') || '').trim();
        const form_inputs = Array.from(formData.entries());
        // const phone = String(formData.get('email') || '').trim();
        // const body = String(formData.get('job') || '').trim();

        // console.log(Array.from(formData.entries()));

        if (!name_form) {
            setMessage('Эта форма не работает', 'error');
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
        } catch (errors) {            
            setMessage('Не удалось отправить сообщение. Попробуйте ещё раз. '+ errors_my, 'error');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    });
}
