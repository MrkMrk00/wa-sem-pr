const API_URLS = {
    manufacturer: '/api/manufacturer',
    bodyStyle: '/api/body_style',
    engine: '/api/engine'
}

export default function ($) {
    const manufacturerForm = $('form[name=manufacturer][ajax-form]')
    const bodyStyleForm = $('form[name=body_style][ajax-form]')
    const engineForm = $('form[name=engine][ajax-form]')

    $('form[ajax-form]').find('.form-title').append('<i class="ajax-form-check"></i>')

    manufacturerForm.submit(handleSubmit(manufacturerForm, API_URLS.manufacturer))
    bodyStyleForm.submit(handleSubmit(bodyStyleForm, API_URLS.bodyStyle))
    engineForm.submit(handleSubmit(engineForm, API_URLS.engine))
}

function handleSubmit(formElement, actionUrl) {
    return async (event) => {
        event.preventDefault()
        formDefault(formElement)
        const formData = new FormData(event.target)

        const res = await fetch(actionUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(formData)
        })
        await handleValidity(res, formElement)
    }
}

function formDefault(formElement) {
    try {
        formElement.find('input[class*="is-invalid"]').removeClass('is-invalid')
        formElement.find('.invalid-feedback').remove()
        formElement.find('.remove-me').remove()
        formElement.find('.ajax-form-check').hide()
    } catch (e) {}
}

async function handleValidity(response, formElement) {
    const json = await response.json()
    console.log(json)
    if (response.status !== 201) {
        for (const error of json) {
            const formName = formElement.attr('name')
            const invalidInputNameRegexp = /^.*\.(.+)$/.exec(error.cause?.propertyPath)
            const invalidInputName = invalidInputNameRegexp ? invalidInputNameRegexp[1] : null;
            console.log(invalidInputName)
            if (!invalidInputName) {
                formElement.append(`<div class="text-danger remove-me">${error.message}</div>`)
                break;
            }
            const input = formElement.find(`input[name="${formName}[${invalidInputName}]"]`)
            input.addClass('is-invalid')
            input.after(`<div class="invalid-feedback">${error.message}</div>`)
        }
        return
    }
    formElement.find('.ajax-form-check').show()
    formElement.find('input[type!=hidden]').val('')
    await reloadChoices()
}

async function reloadChoices() {
    const carForm = $('form[name=car]')

    const bodyStyles = await (await fetch(API_URLS.bodyStyle)).json()
    const bodyStylesSelect = carForm.find('select[name="car[body_styles][]"]')
    rebuildSelect(bodyStylesSelect, bodyStyles)

    const manufacturers = await (await fetch(API_URLS.manufacturer)).json()
    const manufSelect = carForm.find('select[name="car[manufacturer]"]')
    rebuildSelect(manufSelect, manufacturers)

    const engines = await (await fetch(API_URLS.engine)).json()
    const engineSelect = carForm.find('select[name="car[engines][]"]')
    rebuildSelect(engineSelect, engines)
}

function rebuildSelect(selectElement, newElements) {
    selectElement.children().remove()
    for (const elem of newElements) {
        selectElement.append(`<option value="${elem['id']}">${elem.name}</option>`)
    }
}