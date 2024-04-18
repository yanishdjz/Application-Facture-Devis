let baliseImg = document.getElementById('img_Illustration_model');

let selection = document.getElementById('selectionModel');

selection.addEventListener('change', getModel);


function getModel(){
    switch (selection.value) {
        case '1':
            baliseImg.src = "../../img/app/modeles/modele1.PNG";
            break;
        case '2':
            baliseImg.src = "../../img/app/modeles/modele2.PNG";
            break;
    }
}