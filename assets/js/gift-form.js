document.addEventListener('DOMContentLoaded', function() {
    // DOM references and local state for the gift form interactions.
    const maxImages = 3;
    const storageRadio = document.getElementById('gift-exchange-locker');
    const otherRadio = document.getElementById('gift-exchange-location');
    const addressWrapper = document.getElementById('gift-location-wrapper');
    const imagesButton = document.getElementById('gift-images-button');
    const imagesInput = document.getElementById('images');
    const existingImagesInput = document.getElementById('gift-existing-images');
    const featuredImageIndexInput = document.getElementById('featured_image_index');
    const previewContainer = document.getElementById('image-previews');
    let termsSelect = document.getElementById('terms');
    const termsError = document.getElementById('terms-error');
    const loadingOverlay = document.getElementById('gift-form-loading');
    const form = document.getElementById('gift-form');
    const submitButton = form ? form.querySelector('button[type="submit"][name="submit_gift_post"]') : null;
    let selectedImages = [];
    let featuredImageIndex = 0;

    // Show address input only when "Annan adress" is selected.
    function toggleAddressField() {
        if (!storageRadio || !otherRadio || !addressWrapper) {
            return;
        }

        addressWrapper.hidden = !otherRadio.checked;
    }

    function clearPreviews() {
        if (previewContainer) {
            previewContainer.innerHTML = '';
        }
    }

    // Rebuild all image-related UI whenever local image state changes.
    function refreshImageUI() {
        syncInputFiles(selectedImages);
        syncFeaturedImageIndex(featuredImageIndex);
        renderPreviews(selectedImages);
        updateImagesButtonState(selectedImages);
    }

    // Mark one image as featured (used by backend as primary image index).
    function setFeaturedImage(index) {
        if (index < 0 || index >= selectedImages.length) {
            return;
        }

        featuredImageIndex = index;
        refreshImageUI();
    }

    // Remove image and keep featured index consistent after list changes.
    function removeImage(index) {
        if (index < 0 || index >= selectedImages.length) {
            return;
        }

        selectedImages.splice(index, 1);

        if (selectedImages.length === 0) {
            featuredImageIndex = 0;
        } else if (index < featuredImageIndex) {
            featuredImageIndex -= 1;
        } else if (index === featuredImageIndex) {
            featuredImageIndex = Math.min(featuredImageIndex, selectedImages.length - 1);
        }

        refreshImageUI();
    }

    // Rotate an image client-side and replace it in the selected image list.
    function rotateImageLeft(index) {
        const file = selectedImages[index];
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function(readerEvent) {
            const image = new Image();
            image.onload = function() {
                const canvas = document.createElement('canvas');
                canvas.width = image.height;
                canvas.height = image.width;

                const context = canvas.getContext('2d');
                if (!context) {
                    return;
                }

                context.translate(0, canvas.height);
                context.rotate(-Math.PI / 2);
                context.drawImage(image, 0, 0);

                canvas.toBlob(function(blob) {
                    if (!blob) {
                        return;
                    }

                    const rotatedFile = new File(
                        [blob],
                        file.name,
                        {
                            type: file.type || 'image/jpeg',
                            lastModified: Date.now(),
                        }
                    );

                    selectedImages[index] = rotatedFile;
                    refreshImageUI();
                }, file.type || 'image/jpeg', 0.92);
            };

            image.src = readerEvent.target.result;
        };

        reader.readAsDataURL(file);
    }

    // Render image previews and attach per-image control buttons.
    function renderPreviews(files) {
        if (!previewContainer) {
            return;
        }

        clearPreviews();

        files.forEach(function(file, index) {
            const item = document.createElement('div');
            item.className = 'image-preview-item';

            const img = document.createElement('img');
            img.alt = file.name;

            const controls = document.createElement('div');
            controls.className = 'image-preview-controls';

            const featureButton = document.createElement('button');
            featureButton.type = 'button';
            featureButton.className = 'feature-button';
            featureButton.textContent = '⭐';
            featureButton.title = 'Sätt som bild nummer 1';
            featureButton.setAttribute('aria-label', 'Sätt som bild nummer 1');
            if (index === featuredImageIndex) {
                featureButton.classList.add('is-featured');
            }
            featureButton.addEventListener('click', function() {
                setFeaturedImage(index);
            });

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = '❌';
            removeButton.title = 'Ta bort bild';
            removeButton.setAttribute('aria-label', 'Ta bort bild');
            removeButton.addEventListener('click', function() {
                removeImage(index);
            });

            const rotateButton = document.createElement('button');
            rotateButton.type = 'button';
            rotateButton.textContent = '🔄';
            rotateButton.title = 'Rotera vänster';
            rotateButton.setAttribute('aria-label', 'Rotera vänster');
            rotateButton.addEventListener('click', function() {
                rotateImageLeft(index);
            });

            controls.appendChild(featureButton);
            controls.appendChild(removeButton);
            controls.appendChild(rotateButton);

            item.appendChild(img);
            item.appendChild(controls);
            previewContainer.appendChild(item);

            const reader = new FileReader();
            reader.onload = function(event) {
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    function syncInputFiles(files) {
        // Keep input.files aligned with the accumulated selections.
        if (typeof DataTransfer === 'undefined') {
            return;
        }

        const transfer = new DataTransfer();

        files.forEach(function(file) {
            transfer.items.add(file);
        });

        imagesInput.files = transfer.files;
    }

    // Persist featured image index in hidden input for PHP handling.
    function syncFeaturedImageIndex(index) {
        if (!featuredImageIndexInput) {
            return;
        }

        featuredImageIndexInput.value = String(index);
    }

    // Disable image picker button when max number of images is reached.
    function updateImagesButtonState(files) {
        if (!imagesButton) {
            return;
        }

        const isDisabled = files.length >= maxImages;
        imagesButton.disabled = isDisabled;
        imagesButton.classList.toggle('disabled', isDisabled);
    }

    // Prevent duplicate submissions and show loading overlay while posting.
    function showLoadingOverlay() {
        if (!loadingOverlay) {
            return;
        }

        loadingOverlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('gift-form-loading-active');
    }

    function attachTermsSelectHandlers() {
        if (!termsSelect) {
            return;
        }

        termsSelect.addEventListener('change', function() {
            syncTagCountMessage(false);
        });
    }

    // Keep max-tag rule client-side to avoid unnecessary postback/image reset.
    function syncTagCountMessage(showMessage) {
        if (!termsSelect) {
            return true;
        }

        const selectedCount = Array.from(termsSelect.selectedOptions || []).length;
        const isValid = selectedCount <= 3;
        const message = isValid ? '' : 'Du kan välja högst tre kategorier.';

        if (termsError) {
            termsError.textContent = message;
            termsError.hidden = !showMessage || isValid;
        }

        if (!isValid && showMessage) {
            return false;
        }

        return isValid;
    }

    // Parse and validate existing images payload (edit mode only).
    function getExistingImages() {
        if (!existingImagesInput || !existingImagesInput.value) {
            return [];
        }

        try {
            const parsed = JSON.parse(existingImagesInput.value);
            if (!Array.isArray(parsed)) {
                return [];
            }

            return parsed.filter(function(image) {
                return image && typeof image.url === 'string' && image.url;
            });
        } catch (error) {
            return [];
        }
    }

    // Convert an existing image URL into a File so the UI can handle it uniformly.
    async function fileFromExistingImage(image, index) {
        const response = await fetch(image.url, { credentials: 'same-origin' });
        if (!response.ok) {
            throw new Error('Could not fetch existing image.');
        }

        const blob = await response.blob();
        const fallbackName = 'existing-image-' + String(index + 1) + '.jpg';
        const fileName = typeof image.name === 'string' && image.name ? image.name : fallbackName;

        return new File([blob], fileName, {
            type: blob.type || 'image/jpeg',
            lastModified: Date.now(),
        });
    }

    // Preload existing post images into the same preview/selection flow.
    async function preloadExistingImages() {
        const existingImages = getExistingImages().slice(0, maxImages);
        if (!existingImages.length) {
            return;
        }

        const preloaded = [];

        for (let index = 0; index < existingImages.length; index += 1) {
            try {
                const file = await fileFromExistingImage(existingImages[index], index);
                preloaded.push(file);
            } catch (error) {
                // Skip broken existing images and continue loading remaining images.
            }
        }

        if (!preloaded.length) {
            return;
        }

        selectedImages = preloaded;
        featuredImageIndex = 0;
        refreshImageUI();
    }

    // Image picker wiring: open dialog, merge selections, refresh previews.
    if (imagesButton && imagesInput) {
        imagesButton.addEventListener('click', function() {
            imagesInput.click();
        });

        imagesInput.addEventListener('change', function() {
            const newFiles = Array.from(imagesInput.files || []);
            selectedImages = selectedImages.concat(newFiles).slice(0, maxImages);
            refreshImageUI();
        });

        refreshImageUI();
        preloadExistingImages();
    }

    // Clear stale max-tag validation once the selection becomes valid again.
    if (termsSelect) {
        attachTermsSelectHandlers();
    }

    // Exchange location wiring: keep address field visibility in sync with radios.
    if (storageRadio && otherRadio && addressWrapper) {
        storageRadio.addEventListener('change', toggleAddressField);
        otherRadio.addEventListener('change', toggleAddressField);
        toggleAddressField();
    }

    // Submission guard: show loader once and block accidental double-click submits.
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!syncTagCountMessage(true)) {
                event.preventDefault();
                return;
            }

            if (form.dataset.loadingShown === '1') {
                event.preventDefault();
                return;
            }

            showLoadingOverlay();
            form.dataset.loadingShown = '1';
        });
    }
});
