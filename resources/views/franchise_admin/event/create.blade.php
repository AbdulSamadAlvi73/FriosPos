@extends('layouts.app')
@section('content')
    <div class="content-body default-height p-5 mt-5">
        <div class="container-fluid rounded border p-5 bg-white">
            <div class="d-flex justify-content-between">
                <h1 style="font-size: 28px; font-weight: bold">
                    Create Event
                </h1>

                <a href="{{ route('franchise.events.index') }}" class="btn btn-primary">
                    Events
                </a>
            </div>
            <form action="{{ route('franchise.events.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="" class="form-label">Event Name</label>
                    <input type="text" name="name" placeholder="Enter event name" class="form-control" required>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="" class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="" class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                </div>


                <table class="table table-bordered rounded mt-5" id="dynamicTable">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="items[]" id="" class="form-select py-3" style="width: 100%">
                                    @foreach ($items as $item)
                                        <option value="{{ $item->fgp_item_id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                {{-- <input type="text" class="form-control" name="name[]" /> --}}
                            </td>
                            <td>
                                <input type="number" class="form-control" placeholder="10" name="quantity[]" required />
                            </td>
                            <td>
                                <span class="btn btn-success action-btn" onclick="addRow(this)">+</span>
                                <span class="btn btn-danger action-btn" onclick="removeRow(this)">−</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button class="btn btn-outline-primary">
                    Submit
                </button>

            </form>
        </div>
    </div>
    {{-- @push('script') --}}
    <script>
        function addRow(button) {
            let row = button.closest("tr");
            let clone = row.cloneNode(true);
            clone.querySelectorAll("input").forEach((input) => (input.value = ""));
            row.parentNode.appendChild(clone);
        }

        function removeRow(button) {
            let row = button.closest("tr");
            let table = document
                .getElementById("dynamicTable")
                .getElementsByTagName("tbody")[0];
            if (table.rows.length > 1) {
                row.remove();
            } else {
                alert("At least one row is required!");
            }
        }
    </script>
    <script>
        setTimeout(() => {
            $('select').selectpicker('destroy'); // Removes Bootstrap Select enhancement            
        // alert(0)
        }, 2000);

    </script>
    {{-- @endpush --}}
@endsection
