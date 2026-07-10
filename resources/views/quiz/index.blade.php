<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('quiz.create') }}" role="button">Tambah Kuis</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Judul Kuis</th>
                        <th scope="col">Kursus</th>
                        <th scope="col">Passing Score</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quizzes as $quiz)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $quiz->title }}</td>
                            <td>{{ $quiz->course->title }}</td>
                            <td>{{ $quiz->passing_score }}</td>
                            <td>
                                <a href="{{ route('quiz.show', $quiz) }}" class="btn btn-info btn-sm">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="{{ route('quiz.edit', $quiz) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('quiz.destroy', $quiz) }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', $(this).data('route'))
            })
        </script>
    @endpush

</x-app>
