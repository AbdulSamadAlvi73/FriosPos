<div style="max-height: 500px; overflow-y: auto; margin-bottom: 20px;">
    <table class="table table-bordered rounded mt-5" id="dynamicTable">
        <thead>
            <tr>
                <th>Orderable</th>
                <th>In-Stock</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach (old('in_stock', [null]) as $i => $stock)
                <tr>
                    <td>
                        <select name="in_stock[]" class="form-control status-select">
                            @if(!empty($pops) && count($pops) > 0)
                            @foreach ($pops as $item)
                                <option value="{{ $item->fgp_item_id }}"{{
                                    old("in_stock.$i") == $item->fgp_item_id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error("in_stock.$i")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <select name="orderable[]" class="form-control status-select">
                            @foreach ($orderDetails as $item)
                                <option value="{{ $item->fgp_item_id }}"{{
                                    old("orderable.$i") == $item->fgp_item_id ? 'selected' : '' }}>
                                    {{ $item->item_name }} - (x{{ $item->total_units }})
                                </option>
                            @endforeach
                        </select>
                        @error("orderable.$i")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="number" name="quantity[]" placeholder="0" class="form-control"
                            value="{{ old("quantity.$i") }}">
                        @error("quantity.$i")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <span class="btn btn-success action-btn" onclick="addRow(this)">+</span>
                        <span class="btn btn-danger action-btn" onclick="removeRow(this)">−</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        // Initialize select2 for staff_assigned (if needed)
        $('select[name="staff_assigned"]').select2({
            placeholder: "Please Select",
        });

        // Initialize selectpicker for existing rows
        initializeSelectpicker();

        // Event delegation for quantity input fields
        $(document).on('input', 'input[name="quantity[]"]', function(event) {
            let input = event.target;
            if (input.value < 0) {
                input.value = 0;
            }
            if (input.value.startsWith('-')) {
                input.value = input.value.substring(1);
            }
        });
    });

    // Dynamically populate options for in_stock and orderable
    const popsOptions = `{!! collect($pops)->map(fn($item) => "<option value='{$item->fgp_item_id}'>{$item->name}</option>")->implode('') !!}`;
    const orderOptions = `{!! collect($orderDetails)->map(fn($item) => "<option value='{$item->fgp_item_id}'>{$item->item_name} - (x{$item->total_units})</option>")->implode('') !!}`;

    // Add a new row
    function addRow(button) {
        let tableBody = document.getElementById("dynamicTable").getElementsByTagName("tbody")[0];

        let newRow = document.createElement("tr");

        newRow.innerHTML = `
            <td>
                <select name="in_stock[]" class="form-control status-select" style="width: 100%">
                    ${popsOptions}  <!-- Dynamically created options for this select -->
                </select>
            </td>
            <td>
                <select name="orderable[]" class="form-control status-select" style="width: 100%">
                    ${orderOptions}  <!-- Dynamically created options for this select -->
                </select>
            </td>
            <td>
                <input type="number" class="form-control" name="quantity[]" min="0" placeholder="0"  />
            </td>
            <td>
                <span class="btn btn-success action-btn" onclick="addRow(this)">+</span>
                <span class="btn btn-danger action-btn" onclick="removeRow(this)">−</span>
            </td>
        `;

        tableBody.appendChild(newRow);

        // Re-initialize selectpicker for new selects
        initializeSelectpicker();
    }

    // Re-initialize selectpicker for all selects
    function initializeSelectpicker() {
        $('.status-select').selectpicker('refresh');
    }

    // Remove a row
    function removeRow(button) {
        let row = button.closest("tr");
        let table = document.getElementById("dynamicTable").getElementsByTagName("tbody")[0];
        if (table.rows.length > 1) {
            row.remove();
        } else {
            alert("At least one row is required!");
        }
    }
</script>
