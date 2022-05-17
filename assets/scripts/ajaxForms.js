export default function ($) {
    const manufacturerForm = $('form[id=manufacturer-custom-form]');
    const bodyStyleForm = $('form[id=body-style-custom-form]');

    manufacturerForm.submit(async function (e) {
        e.preventDefault();
        const dataObject = Object.fromEntries(new FormData(e.target).entries())
        const st = await fetch('/api/manufacturers', {
            method: 'POST',
        })
        console.log(st.headers.get('Content-Type'))
    });
}