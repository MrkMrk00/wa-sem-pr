export default ($) => {
    const manufacturerForm = $('form[name=manufacturer][ajax-form]');
    const bodyStyleForm = $('form[id=body-style-custom-form]');

    manufacturerForm.submit(async function (e) {
        e.preventDefault();
        formDefault($(this))
        const formData = new FormData(e.target)

        const res = await fetch('/api/manufacturer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(formData)
        })
        await handleValidity(res, $(this))
    });
}


const formDefault = (formElement) => {
    try {
        formElement.find('input[class*="is-invalid"]').removeClass('is-invalid')
        formElement.find('div[class*="invalid-feedback"]').remove()
    } catch (e) {}
}

const handleValidity = async (response, formElement) => {
    const json = await response.json()
    if (response.status !== 201) {
        for (const error of json) {
            const formName = formElement.attr('name')
            let invalidInputName = /^\w*\.(.+)$/.exec(error.cause.propertyPath)
            if (!invalidInputName[1]) break;
            invalidInputName = invalidInputName[1]

            const input = formElement.find(`input[name="${formName}[${invalidInputName}]"]`)
            input.addClass('is-invalid')
            input.after(`<div class=\"invalid-feedback\">${error.message}</div>`)
        }
        return
    }

}