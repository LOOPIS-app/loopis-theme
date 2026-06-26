// Helper, reads all values of certain class into name => value array
function getLedgerArray(classCss){
	const options = {};

	document.querySelectorAll(classCss).forEach(element => {
		if (element.value!=''){
			options[element.name] = element.value;
		}
	});

	return options;

}
// loads display function for page
function loadLedgerPage(page=1){
    // get data
	const nonce = window.LoopisLedger.nonce;
  	const ajaxUrl = window.LoopisLedger.ajaxUrl;
	const options = getLedgerArray('.ledger-filter');
	const columns = getLedgerArray('.ledger-column');
	const log = document.getElementById('ledger');
	const pagination = document.getElementById('post-pagination');
	const activityCount = document.getElementById('activity-count');
    // make pseudo post passing data to function
	fetch(ajaxUrl, {
	       	method: 'POST',
	       	headers: {
	           	'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
	       	},
	       	body: new URLSearchParams({
	           	action: 'loopis_ledger_page',
	           	options: JSON.stringify(options),
				columns: JSON.stringify(columns),
	           	page: page,
			   	nonce: nonce
	       	})
	   	})
	   	.then(r => r.json())
	   	.then(data => {
	       	if (!data.success) return;
            // update HTML
	       	log.innerHTML = data.data.activity;
			activityCount.innerHTML = data.data.activityCount;
	       	pagination.innerHTML = data.data.pagination;
	       	pagination.dataset.page = data.data.page;
	       	pagination.dataset.maxPages = data.data.max_pages;
	   	});
}

// if paging clicked move page
document.addEventListener('click', function(e){
	const button = e.target.closest('.loopis_ajax_button');
	if (!button) return;

  	const page = parseInt(button.dataset.page, 10);
  	if (!Number.isFinite(page)) return;
	loadLedgerPage(page);

});

// if filter clicked reload base page with filter
document.getElementById('ledger-filter-btn')?.addEventListener('click', function() {
	loadLedgerPage(1);
});