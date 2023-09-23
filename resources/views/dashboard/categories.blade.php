@extends('layouts.app')

<title>Xhavo Category list</title>
<style>
    .thumbnail {
        width: 20px;
        height: 20px;
        object-fit: cover;
    }

    table tr td {
        border: 0.1rem solid #dee2e6;
    }

    thead tr th {
        background-color: #d4d6d8 !important;
        margin-top: 1rem !important;
    }
</style>

@section('content')
    <!--  Row -->
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
          <div class="card w-100">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-semibold">Category/Department</h5>
                <button type="button" class="btn btn-default xhavo-btn rounded-0" data-bs-toggle="modal" data-bs-target="#newModal">
                    New Category
                  </button>
              </div>
              <div class="table-responsive">
                <table id="xhavoTable" class="table border-striped align-middle">

                    <thead class="text-dark fs-4">
                        <tr style="margin-top: 20px">
                        <th>
                            <h6 class="fw-semibold mb-0">S/N</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Thumbnail</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Title</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Action</h6>
                        </th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $id = 1;
                        @endphp
                        @foreach ($categories as $category)
                            <tr>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $id++ }}</h6></td>
                            <td class="border-bottom-0">
                                <h6 class="fw-semibold mb-1">
                                    <img class="thumbnail" src="/images/categories/{{ $category->thumbnail }}" alt="">
                                </h6>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $category->title }}</p>
                            </td>
                            <td class="border-bottom-0">
                                <div class="d-flex">
                                <a href="#" class="btn btn-primary rounded-0 btn-sm px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">Edit</a>

                                <form class="m-0 p-0" method="POST" action="{{ route('categories.destroy', $category->id) }}">
                                        @method('delete')
                                        @csrf
                                    <button class="btn btn-danger rounded-0 btn-sm px-3" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                                </form>
                                </div>
                            </td>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content py-2">
                                        <div class="modal-header border-bottom">
                                        <h1 class="modal-title fs-5" id="editModalLabel">Edit department.</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="title" class="form-label text-right">Title</label>
                                                    <input type="text" name="title" class="form-control rounded-0" placeholder="Category Name" value="{{ $category->title, old('title') }}" id="title" aria-describedby="titleHelp">
                                                    {{-- If error and else --}}
                                                    @error('title')
                                                        <div id="titleHelp" class="form-text text-danger">{{ $message }}</div>
                                                    @else
                                                        <div id="titleHelp" class="form-text">Enter the title of the department.</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="thumbnail" class="form-label" style="cursor: pointer">Thumbnail
                                                        <img width="100px" src="/images/categories/{{ $category->thumbnail }}" alt="">
                                                    </label>

                                                    <input type="file" name="thumbnail" class="form-control rounded-0" id="thumbnail" aria-describedby="thumbnailHelp">
                                                    {{-- If error and else --}}
                                                    @error('thumbnail')
                                                        <div id="thumbnailHelp" class="form-text text-danger">{{ $message }}</div>
                                                    @else
                                                        <div id="thumbnailHelp" class="form-text">Upload the thumbnail of the department.</div>
                                                    @enderror
                                                </div>

                                                {{-- submit buttom --}}
                                                <button type="submit" class="btn xhavo-btn btn-block w-100 rounded-0">Save changes</button>
                                            </form>
                                        </div>

                                    </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>

    </div>



    <!-- New -->
    <div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content py-2">
            <div class="modal-header border-bottom">
            <h1 class="modal-title fs-5" id="newModalLabel">New department.</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label text-right">Title</label>
                    <input type="text" name="title" class="form-control rounded-0" placeholder="Category Name" id="title" aria-describedby="titleHelp">
                    {{-- If error and else --}}
                    @error('title')
                        <div id="titleHelp" class="form-text text-danger">{{ $message }}</div>
                    @else
                        <div id="titleHelp" class="form-text">Enter the title of the department.</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control dropify rounded-0" id="thumbnail" aria-describedby="thumbnailHelp">
                    {{-- If error and else --}}
                    @error('thumbnail')
                        <div id="thumbnailHelp" class="form-text text-danger">{{ $message }}</div>
                    @else
                        <div id="thumbnailHelp" class="form-text">Upload the thumbnail of the department.</div>
                    @enderror
                </div>

                {{-- submit buttom --}}
                <button type="submit" class="btn xhavo-btn btn-block w-100 rounded-0">Save changes</button>
            </form>
            </div>

        </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.dropify2').dropify();
        });
    </script>
@endsection
