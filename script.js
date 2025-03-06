document.addEventListener('DOMContentLoaded', function () {
    const descriptionTextarea = document.getElementById('description');
    const addBotForm = document.querySelector('.add-bot-form');

    // Ajuster la hauteur et la largeur du formulaire en fonction de la taille de la description
    descriptionTextarea.addEventListener('input', function () {
        addBotForm.style.height = 'auto';
        addBotForm.style.width = 'auto';
        addBotForm.style.height = (descriptionTextarea.scrollHeight + 20) + 'px'; // Ajoutez un padding supplémentaire
        addBotForm.style.width = (descriptionTextarea.scrollWidth + 20) + 'px'; // Ajoutez un padding supplémentaire
    });
});
