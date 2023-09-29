<div class="card-footer d-flex justify-content-end " wire:key="register-users-wait">
<ul>
  <!-- Previous Page Link -->
  @if ($paginator->onFirstPage())
      <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
          <span aria-hidden="true">&lsaquo;</span>
      </li>
  @else
      <li>
          <button wire:click="gotoPage({{ $paginator->currentPage() - 1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</button>
      </li>
  @endif

  <!-- Next Page Link -->
  @if ($paginator->hasMorePages())
      <li>
          <button wire:click="gotoPage({{ $paginator->currentPage() + 1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</button>
      </li>
  @else
      <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
          <span aria-hidden="true">&rsaquo;</span>
      </li>
  @endif
</ul>
</div>