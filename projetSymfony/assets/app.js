import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

const addToCart = document.querySelectorAll('.addToCartCB');

addToCart.forEach((element) => {
    // si la checkbox est cochée, rendre quantity visible
    element.addEventListener('click', (e) => {
        const quantity = e.target.closest('.card').querySelector('.quantity');
        if (e.target.checked) {
            quantity.classList.remove('quantityHidden');
            quantity.classList.add('quantityVisible');
        } else {
            quantity.classList.remove('quantityVisible');
            quantity.classList.add('quantityHidden');
        }
    });
});

const btnAddToList = document.querySelector('.btnAddToList');
const modalAdd = document.getElementById('modalAdd');
const btnCloseModalAdd = document.getElementById('btnCloseModalAdd');
const btnConfirmAddToList = document.getElementById('btnConfirmAddToList');

btnAddToList.addEventListener('click', function() {
    modalAdd.style.display = 'block';
});

btnCloseModalAdd.addEventListener('click', function() {
    modalAdd.style.display = 'none';
});



btnConfirmAddToList.addEventListener('click', function() {
    modalAdd.style.display = 'none';
    console.log('Checkout button clicked');
    // Récupérer les tuples d'articles
    var articlesData = createArticleTuples();
    console.log('Articles data: ');
    console.log(articlesData);
    // Récupérer l'id de la liste
    var listId = document.getElementById("listeSelect").value;

    // Créer les tuples d'articles en dur

    


    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/contient/add-to-list/'+ listId, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {

                console.log('Articles ajoutés à la liste avec succès');
            } else {
                // Gérer les erreurs
                console.error('Erreur lors de l\'ajout des articles à la liste');
            }
        }
    };
    xhr.send(JSON.stringify({ articles: articlesData }));
});

function createArticleTuples() {
    var articleTuples = [];
    console.log('createArticleTuples() called');
    // Sélectionner tous les éléments avec la classe "addToCartCB"
    var checkboxes = document.querySelectorAll('.addToCartCB');

    // Parcourir chaque case à cocher
    checkboxes.forEach(function(checkbox) {
        // Vérifier si la case à cocher est cochée
        if (checkbox.checked) {
            console.log('Checkbox checked');
            // Récupérer l'ID de l'article associé à la case à cocher
            var articleId = checkbox.parentElement.getAttribute('data-article-id');
            console.log('Article ID: ' + articleId);
            // Récupérer la quantité associée à la case à cocher
            var quantityInput = checkbox.closest('.card').querySelector('.quantity');
            var quantity = quantityInput.value;

            // Ajouter le tuple d'article à la liste
            articleTuples.push({ articleId: articleId, quantity: quantity });
        }
    });

    return articleTuples;
}





