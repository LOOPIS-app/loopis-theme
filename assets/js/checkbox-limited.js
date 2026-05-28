(function() {

let imageStore = [];

document.addEventListener('DOMContentLoaded', () => {
    imageStore = [];
    loopisPreviewImageRendering();
        if (typeof existingImages !== 'undefined') {

        existingImages.forEach((img, index) => {

            loopisPImage(
                index,
                img.src,
                'image-previews',
                true,
                null,
                null
            );
            const image = document.getElementById(`img-${index}`);
                

            // highlight current featured image
            if (img.thumbnail) {

                if (image) {
                    image.classList.add('green-border');
                }

                document.getElementById('thumb').value = index;
            }
        });
    }
    loopisCheckboxLimit();
    loopisRadioListener();
    loopisSubmissionStopper();
})


function loopisSubmissionStopper(){
    const form = document.getElementById('loopis-form');
    if (!form) return;
    const title = document.getElementById('post_title');
    const content = document.getElementById('post_content');
    const checkboxes = document.querySelectorAll('.term_checkbox'); 
    const imageInput = document.getElementById('images');
    const where = document.getElementById('where');
    const annan = document.getElementById('annan');
    const radioboxes = document.querySelectorAll('input[name="locker"]');
    const categoryWarning = document.getElementById('category_warning');
    const imageWarning = document.getElementById('image_warning');
    const radioWarning = document.getElementById('radio_warning');
    const titleWarning = document.getElementById('title_warning');
    const contentWarning = document.getElementById('content_warning');

    title.addEventListener('input', ()=>{
            titleWarning.classList.remove('warning');
            titleWarning.classList.add('secret');
    })
    content.addEventListener('input', ()=>{
            contentWarning.classList.remove('warning');
            contentWarning.classList.add('secret');
    })

    form.addEventListener('submit', (e) => {
        let valid = true;
        const imageAmount = imageInput.files.length;
        if (imageAmount === 0){
            if (!document.getElementById('img-0')){
                 valid = false;
                imageWarning.classList.add('warning');
                imageWarning.classList.remove('secret');
            }
        }

        if (title.value.trim() === ''){
            valid = false;
            titleWarning.classList.add('warning');
            titleWarning.classList.remove('secret');
        }

        if (content.value.trim() === ''){
            valid = false;
            contentWarning.classList.add('warning');
            contentWarning.classList.remove('secret');
        }

        let categoryChecked = Array.from(checkboxes).some(check => check.checked);
        if (!categoryChecked){
            valid = false;
            categoryWarning.classList.add('warning');
            categoryWarning.classList.remove('secret');
        }

        let radioChecked = Array.from(radioboxes).some(radio => radio.checked);
        if (!radioChecked){
            valid = false;
            radioWarning.classList.add('warning');
            radioWarning.classList.remove('secret');
        }else if(where.value.trim() === ''){
            if (annan.checked){
                valid = false;
                radioWarning.classList.add('warning');
                radioWarning.classList.remove('secret');
            }
        }

        if (!valid){
            e.preventDefault();
        }

    });
}

function loopisRadioListener(){
    const radioBox = document.getElementById('radio-box');
    const where = document.getElementById('where');
    const annan = document.getElementById('annan');
    const radioWarning = document.getElementById('radio_warning');
    if (!radioBox || !where || !annan || !radioWarning) return;
    
    radioBox.addEventListener('change', () => {
        if (annan.checked){
            if(where.value !== ""){
                radioWarning.classList.remove('warning');
                radioWarning.classList.add('secret');
            }
        }else{
            radioWarning.classList.remove('warning');
            radioWarning.classList.add('secret');
        }
    })
}

function loopisCheckboxLimit(){

    const max = 3;
    const checkboxes = document.querySelectorAll('.term_checkbox'); 
    const categoryWarning = document.getElementById('category_warning');


    function checkenable(){
        checkboxes.forEach(check =>{
            check.disabled=false;
        })
    }
    function checkdisable(){
        checkboxes.forEach(check =>{
            check.disabled=true;
        })
    }

    if (!checkboxes || !categoryWarning) return;
    const termlist = document.getElementById('termlist');
    const checkedboxes =  document.querySelectorAll('.term_checkbox:checked');
    console.log(checkedboxes);
    checkedboxes.forEach(check =>{
        if (!termlist.querySelector('#' + check.id)){
            termlist.appendChild(loopisCheckboxClicker(check.id,  check, checkenable));
        }
    })

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {

            const checkedboxes =  document.querySelectorAll('.term_checkbox:checked');
            const count = checkedboxes.length;

            categoryWarning.classList.remove('warning');
            categoryWarning.classList.add('secret');

            if (count>=max){
                checkdisable();

                checkedboxes.forEach(check =>{
                    if (!termlist.querySelector('#' + check.id)){
                        termlist.appendChild(loopisCheckboxClicker(check.id,  check, checkenable));
                    }
                })
            }else{
                checkenable();

                checkedboxes.forEach(check =>{
                    if (!termlist.querySelector('#' + check.id)){
                        termlist.appendChild(loopisCheckboxClicker(check.id,  check, checkenable));
                    }
                })
            }

        })
    })
}

function loopisPreviewImageRendering(){
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById( 'image-previews');
    const imageWarning = document.getElementById('image_warning');

    if (!imageInput || !previewContainer || !imageWarning) return;  
    //const activeExisting = imageStore.filter(i => !i.isRemoved).length;

    imageInput.addEventListener('change', () => {
        const files = Array.from(imageInput.files);
        if ((files.length )>3){
            imageWarning.classList.add('warning');
            imageWarning.classList.remove('secret');
            imageInput.value = ''; 
            return;
        }
        imageInput.value = ''; 
        previewContainer.innerHTML = '';

        imageWarning.classList.remove('warning');
        imageWarning.classList.add('secret');


        files.forEach((file, index) => {
            handleUpload(file, index);
        });
    });
}


function handleUpload(file, index) {
    const reader = new FileReader();
    reader.onload = function(e) {
        loopisPImage(index, e.target.result, "image-previews", false, null, file);
    };
    reader.readAsDataURL(file);
}



function loopisPImage(index, src, container, old=false, input, file=null,){
    const thumb = document.getElementById("thumb");

    const imageInput = input ? document.getElementById(input) : null;
    const previewContainer = document.getElementById(container);

    if (!previewContainer) {
        console.warn("Missing container:", container);
        return;
    }

    const state = {
        id: index,
        index,
        src,
        file,
        isRemoved: false,
        isThumbnail: false,
        old: old 
    };

    imageStore.push(state);

    // Container for img and related input elements
    const wrapper = document.createElement('div');
    wrapper.style.position = 'relative';
    wrapper.style.display = 'inline-block';
    wrapper.id = `img-wrapper-${index}`;    
    wrapper.dataset.imageIndex = index;

    // Img file-preview element
    const img = document.createElement('img');
    img.classList.add('image-prev');
    img.src = src;
    img.id = `img-${index}`;     

    const removeInput = document.createElement('input');
    removeInput.type = 'hidden';
    if(old){
        removeInput.name = `remove_old_${index}`;
        removeInput.id = `remove_old_${index}`;
    } else{
        removeInput.name = `remove_${index}`;
        removeInput.id = `remove_${index}`;
    }
    removeInput.value = 0;
    wrapper.appendChild(removeInput);
    

    // Rotate input element
    let rotate = 0;  
    const rotateInput = document.createElement('input');
    rotateInput.type = 'hidden';
    rotateInput.value = 0;
    rotateInput.id = `rotation_${index}`;        
    rotateInput.name = `rotation_${index}`;

    // Add preview image element-tree to form
    wrapper.appendChild(img);
    wrapper.appendChild(rotateInput);
    previewContainer.appendChild(wrapper); 
                
    // Basic structure of an overlay with buttons
    // Add overlay which appends itself to document body
    const overlay = loopisOverlay();
    // Add an overlay display trigger
    img.onclick = () => { overlay.classList.add('overlay-visible');}
    // subcontainer within overlay, with text Hantera bild and closing function that closes the overlay window
    const subcontainer = loopisSubcontainer(overlay, 'Hantera bild', () => {overlay.classList.remove('overlay-visible')});
    // Add button with overlay as container
    const removeBtn = loopisButtonIcon(subcontainer);
    // Add button content
    removeBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
    // Add loopis colour or styling class
    removeBtn.classList.add('red');

    // Three more in the same style
    const setPrimaryBtn = loopisButtonIcon(subcontainer);
    setPrimaryBtn.innerHTML = '<i class="fa-solid fa-star"></i>';
    setPrimaryBtn.classList.add('green');

    const rotateRBtn = loopisButtonIcon(subcontainer);
    rotateRBtn.innerHTML = '<i class="fa-solid fa-rotate-right"></i>';
    rotateRBtn.classList.add('blue');

    const rotateLBtn = loopisButtonIcon(subcontainer);
    rotateLBtn.innerHTML = '<i class="fa-solid fa-rotate-left"></i>';
    rotateLBtn.classList.add('blue');
                
    // Add button functionality to onclick
    removeBtn.onclick = () => removeIMG();
    rotateRBtn.onclick = () => rotateIMG(+1);
    rotateLBtn.onclick = () => rotateIMG(-1);
    setPrimaryBtn.onclick = () =>  setThumbnail();
    removeInput.value = 1;
    if (state.id===0){
        setThumbnail();          
    }
    function removeIMG() {        
        state.isRemoved = true;
        removeInput.value= true;
        wrapper.remove();
        overlay.classList.remove('overlay-visible');
        if (state.isThumbnail) {
            const next = imageStore.find(i =>
                !i.isRemoved && i.id !== state.id
            );
            if (next) {
                next.isThumbnail = true;
                thumb.value = next.id;
                const nextImg = document.getElementById(`img-${next.id}`);
                if (nextImg) {
                    nextImg.classList.add('green-border');
                }
            }
        }
    };

    function rotateIMG(sign) {
        state.rotation += sign*90;                      // change rotate value
        state.rotation = ((state.rotation % 360) + 360) % 360;
        img.style.transform = `rotate(${state.rotation}deg)`;   // rotate preview image
        rotateInput.value = state.rotation;             // store the new rotation value in the form
        overlay.classList.remove('overlay-visible');    // stop displaying the overlay
    };

    function setThumbnail(){
        imageStore.forEach(i => i.isThumbnail = false);
        state.isThumbnail = true;                           // set thumbnail value
        thumb.value = index;
        document.querySelectorAll('[id^="img-"]').forEach( image =>{
            image.classList.remove('green-border');     // unhighlight previous
        });
        img.classList.add('green-border');              // highlight thumbnail
        overlay.classList.remove('overlay-visible');    // stop displaying the overlay
    }
}


function loopisOverlay() {
    // Creates an overlay like the one in post forms, in front of headers and all such and returns object
    const overlay = document.createElement('div');
    overlay.classList.add('loopis-overlay');
    // Appends it to the document body
    document.body.appendChild(overlay);
    // If you click the overlay it disappears
    overlay.onclick = (e) => {
        if (e.target === overlay) overlay.classList.remove('overlay-visible');;
    };
    return overlay;
}

function loopisSubcontainer(container, text, closeFunc){
    // Make
    const subcontainer = document.createElement('div')
    const head = document.createElement('div')
    head.style.height = '20%';
    const body = document.createElement('div')
    body.style.height = '80%';  
    const textElement = document.createElement('span');

    textElement.innerHTML = `<h2>${text}</h2>`;
    
    // Create close button
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '<i class="fa-solid fa-x"></i>';
    closeBtn.onclick = () => closeFunc();
    subcontainer.classList.add('loopis-alert');
    body.classList.add('loopis-alert-body');
    head.classList.add('loopis-alert-head');
    subcontainer.style.display = 'flex';
    subcontainer.style.flexDirection = 'column';

    head.appendChild(textElement)

    head.appendChild(closeBtn)
    subcontainer.appendChild(head)
    subcontainer.appendChild(body)
    container.appendChild(subcontainer)
    return body;
}

function loopisCheckboxClicker(text,  checkbox, checkenable){

    const box = document.createElement('div');
    box.id = text;
    box.classList.add('loopis-box');

    const textElement = document.createElement('span');
    textElement.innerHTML = `<p>${text}</p>`;

    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '<i class="fa-solid fa-x fa-2xs"></i>';
    closeBtn.onclick = () => {
        checkbox.style.display = 'flex';
        checkbox.checked = false;
        checkenable();
        box.remove();
    }; 

    box.appendChild(textElement);
    box.appendChild(closeBtn);
   
    return box;
}


function loopisButton({container=document.createElement('div'), IHTML='', clickFunc = ()=>{}, classaddition=''}={}){

    const button = document.createElement('button');

    button.innerHTML = IHTML;

    button.classList.add('loopis-button');

    if (classaddition){
        button.classList.add(classaddition);
    }

    container.appendChild(button);

    button.onclick = () => clickFunc();

    return button;
}

function loopisButtonIcon(container){
    
    const button = document.createElement('button');

    button.classList.add('loopis-button');

    container.appendChild(button);

    return button;
}

}());
