async function imageLoader() {
  const loader = document.getElementById('loading-indicator');
  if (loader) loader.style.display = 'flex';

  $.post(loopis_theme_ajax.ajax_url, {
    action: 'loopis_image_post_processing',
    nonce: loopis_theme_ajax.nonce,
    postid: loopis_theme_ajax.post_id
  }).done(function (response) {
    // Safeguards
    if (response && response.success && Array.isArray(response.data.images)) {
      const images = response.data.images;
      const postImage = document.querySelector('.post-image');
      if (postImage && images[0]) postImage.innerHTML = images[0];
      if (images.length > 1) {
        const extra1 = document.querySelector('.extra-image');
        if (extra1) extra1.innerHTML = images[1];
      }
      if (images.length > 2) {
        const extra2 = document.querySelector('.extra-image-2');
        if (extra2) extra2.innerHTML = images[2];
      }
    } else {
      console.error('Image processing failed: invalid response', response);
    }
  }).fail(function () {
    console.error('Image processing failed: AJAX error');
  }).always(function () {
    if (loader) loader.style.display = 'none';
  });
}
