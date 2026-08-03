{{--
    Shared Delete-Confirmation Modal Partial
    Usage: @include('partials.delete-modal')

    Trigger from any page:
        openDeleteModal(deleteUrl, 'Item Name', 'optional detail text')

    The modal submits a hidden form with POST + @method('DELETE').
--}}

<style>
    /* ── Delete modal ────────────────────────────────── */
    #shared-delete-backdrop {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.65);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 9000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    #shared-delete-backdrop.open { display: flex; }
    #shared-delete-modal {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl, 28px);
        width: 100%;
        max-width: 420px;
        animation: del-modal-in .25s cubic-bezier(.4,0,.2,1);
        overflow: hidden;
    }
    @keyframes del-modal-in {
        from { opacity:0; transform:scale(.94) translateY(18px); }
        to   { opacity:1; transform:scale(1)   translateY(0); }
    }
    .del-modal-body {
        padding: 32px 28px 28px;
        display: flex; flex-direction: column; align-items: center;
        text-align: center; gap: 12px;
    }
    .del-modal-icon-ring {
        width: 64px; height: 64px; border-radius: 50%;
        background: rgba(244,63,94,.12);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 4px;
    }
    .del-modal-icon-ring svg { width: 30px; height: 30px; color: #f43f5e; }
    #del-modal-title {
        font-size: 1.15rem; font-weight: 800;
        color: var(--text-primary); margin: 0;
    }
    #del-modal-subtitle {
        font-size: .875rem; color: var(--text-secondary);
        line-height: 1.65; margin: 0; max-width: 320px;
    }
    #del-modal-detail {
        font-size: .8rem; color: var(--text-muted);
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm, 8px);
        padding: 6px 14px;
        display: none;
    }
    .del-modal-footer {
        display: flex; gap: 10px;
        padding: 0 28px 28px;
    }
    .del-modal-footer .btn { flex: 1; justify-content: center; }
    .del-btn-cancel {
        background: var(--bg-elevated);
        color: var(--text-secondary);
        border: 1px solid var(--border);
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 10px 18px; border-radius: var(--radius-sm, 8px);
        font-size: .875rem; font-weight: 600; cursor: pointer;
        transition: var(--transition,.25s);
    }
    .del-btn-cancel:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .del-btn-confirm {
        background: rgba(244,63,94,.12);
        color: #f43f5e;
        border: 1px solid rgba(244,63,94,.3);
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 10px 18px; border-radius: var(--radius-sm, 8px);
        font-size: .875rem; font-weight: 600; cursor: pointer;
        transition: var(--transition,.25s);
    }
    .del-btn-confirm:hover { background: rgba(244,63,94,.22); border-color: rgba(244,63,94,.5); }
    .del-btn-confirm svg,
    .del-btn-cancel svg { width: 15px; height: 15px; }
</style>

<div id="shared-delete-backdrop" role="dialog" aria-modal="true" aria-labelledby="del-modal-title">
    <div id="shared-delete-modal">
        <div class="del-modal-body">
            <div class="del-modal-icon-ring">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
            </div>
            <h2 id="del-modal-title">Confirm Delete</h2>
            <p id="del-modal-subtitle">This action <strong>cannot be undone</strong>. The record will be permanently removed.</p>
            <span id="del-modal-detail"></span>
        </div>

        <div class="del-modal-footer">
            <button type="button" class="del-btn-cancel" id="del-cancel-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
                Cancel
            </button>

            <form id="del-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="del-btn-confirm" id="del-confirm-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * openDeleteModal(url, itemName, detailText)
     * @param {string} url         - The DELETE endpoint, e.g. '/products/5'
     * @param {string} itemName    - Shown in the subtitle, e.g. '"Premium Headphones"'
     * @param {string} [detail]    - Optional extra warning text
     */
    function openDeleteModal(url, itemName, detail) {
        const backdrop = document.getElementById('shared-delete-backdrop');
        const form     = document.getElementById('del-form');
        const subtitle = document.getElementById('del-modal-subtitle');
        const detailEl = document.getElementById('del-modal-detail');

        form.action = url;
        subtitle.innerHTML = 'Are you sure you want to delete <strong>' +
            itemName.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;') +
            '</strong>? This action <strong>cannot be undone</strong>.';

        if (detail) {
            detailEl.textContent = detail;
            detailEl.style.display = 'block';
        } else {
            detailEl.style.display = 'none';
        }

        backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
        document.getElementById('del-cancel-btn').focus();
    }

    function closeDeleteModal() {
        document.getElementById('shared-delete-backdrop').classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('del-cancel-btn').addEventListener('click', closeDeleteModal);
    document.getElementById('shared-delete-backdrop').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
