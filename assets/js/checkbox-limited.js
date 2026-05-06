
document.addEventListener('DOMContentLoaded', () => {
    const max = 3;
    const checkboxes = document.querySelectorAll('.term_checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const count = document.querySelectorAll('.term_checkbox:checked').length;
            if (count>=max){
                checkboxes.forEach(check =>{
                    if (!check.checked){
                        check.disabled=true;
                    }
                })
            }else{
                checkboxes.forEach(check =>{
                    check.disabled=false;
                })
            }
        })
    })

    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-previews');

    imageInput.addEventListener('change', () => {
    const files = Array.from(imageInput.files);
    if (files.length>3){
        alert(`You can only upload up to 3 images.`);
        previewContainer.innerHTML = '';
        imageInput.value = ''; 
        return;
    }
    
    previewContainer.innerHTML = '';
    files.forEach(file => {
      const reader = new FileReader();
      reader.onload = e => {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.width = '120px';
        img.style.height = '120px';
        img.style.objectFit = 'cover';
        img.style.border = '1px solid #ccc';
        img.style.borderRadius = '6px';
        previewContainer.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
    });
})
