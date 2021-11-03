<div class="d-flex justify-content-center">
  <ul class="pagination bg-primary">

    @if ($current_page == 1)
      <li class="page-item disabled">
        <a class="page-link" href="/blog" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <li class="page-item disabled">
        <a class="page-link" href="/blog">Previous</a>
      </li>          
    @else
    <li class="page-item">
      <a class="page-link" href="/blog?page=1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
      <li class="page-item">
        <a class="page-link" href="/blog?page={{ request('page')-1 }}">Previous</a>
      </li>          
    @endif

    @if (ceil($total_data / $per_page) == $current_page)
      <li class="page-item disabled">
        <a class="page-link" href="/blog">Next</a>
      </li>  
      <li class="page-item disabled">
        <a class="page-link" href="/blog" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    @else
      <li class="page-item">
        <a class="page-link" href="/blog?page={{ request('page')? request('page')+1 : 2 }}">Next</a>
      </li>   
      <li class="page-item">
        <a class="page-link" href="/blog?page={{ ceil($total_data / $per_page) }}" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li> 
    @endif

  </ul>
</div>