<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="bi bi-receipt text-info fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0">លម្អិតប្រតិបត្តិការ</h5>
                        <small class="text-info opacity-75">លេខយោង: <span id="modal-ref-no" class="fw-bold"></span></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="p-4 bg-light border-bottom">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="text-muted small d-block text-uppercase mb-1">អ្នកផ្គត់ផ្គង់</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building me-2 text-primary"></i>
                                <h6 id="modal-supplier-name" class="fw-bold mb-0"></h6>
                            </div>
                        </div>
                        <div class="col-sm-4 text-sm-center border-start border-end">
                            <label class="text-muted small d-block text-uppercase mb-1">កាលបរិច្ឆេទ</label>
                            <div class="d-flex align-items-center justify-content-sm-center">
                                <i class="bi bi-calendar-event me-2 text-primary"></i>
                                <h6 id="modal-date" class="fw-bold mb-0"></h6>
                            </div>
                        </div>
                        <div class="col-sm-4 text-sm-end">
                            <label class="text-muted small d-block text-uppercase mb-1">ស្ថានភាព</label>
                            <span class="badge bg-success-subtle text-success px-3 rounded-pill">សម្រេច</span>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>បញ្ជីទំនិញ</h6>
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase">
                                    <th class="ps-3 py-3" style="width: 40%;">ឈ្មោះទំនិញ</th>
                                    <th class="text-center py-3">ចំនួន</th>
                                    <th class="text-end py-3">តម្លៃដើម</th>
                                    <th class="text-end py-3">បញ្ចុះតម្លៃ</th>
                                    <th class="text-end pe-3 py-3">សរុប</th>
                                </tr>
                            </thead>
                            <tbody id="modal-items-body" class="border-0">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-4 bg-light">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">អរគុណសម្រាប់ការពិនិត្យទិន្នន័យ</span>
                    <button type="button" class="btn btn-dark px-4 rounded-3" data-bs-dismiss="modal">បិទ</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function viewPurchaseDetails(id) {
        const myModal = new bootstrap.Modal(document.getElementById('detailModal'));
        const itemsList = document.getElementById('modal-items-body');
        const refTitle = document.getElementById('modal-ref-no');
        const supplierName = document.getElementById('modal-supplier-name');
        const purchaseDate = document.getElementById('modal-date');

        // បង្ហាញ Loading Spinner ស្អាតជាងមុន
        itemsList.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <div class="text-muted small">កំពុងទាញទិន្នន័យ...</div>
                </td>
            </tr>`;

        myModal.show();

        fetch(`/reports/purchase/${id}`)
            .then(response => response.json())
            .then(data => {
                refTitle.innerText = data.reference_no;
                supplierName.innerText = data.supplier ? data.supplier.name : 'N/A';
                purchaseDate.innerText = data.purchase_date;

                let rows = '';
                if(data.items && data.items.length > 0) {
                    data.items.forEach(item => {
                        rows += `
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">${item.product ? item.product.name : 'ទំនិញ #' + item.product_id}</div>
                                    <small class="text-muted">ID: ${item.product_id}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2">${item.quantity}</span>
                                </td>
                                <td class="text-end fw-semibold text-secondary">$${parseFloat(item.unit_cost).toFixed(2)}</td>
                                <td class="text-end text-danger">$${parseFloat(item.discount).toFixed(2)}</td>
                                <td class="text-end pe-3 fw-bold text-navy">$${parseFloat(item.subtotal).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                } else {
                    rows = '<tr><td colspan="5" class="text-center py-5 text-muted">មិនមានទិន្នន័យទំនិញឡើយ</td></tr>';
                }
                itemsList.innerHTML = rows;
            })
            .catch(error => {
                itemsList.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-5">កំហុសក្នុងការទាញទិន្នន័យ!</td></tr>';
            });
    }
</script>
