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
                        <select name="in_stock[]" class="form-control">
                            @foreach ($pops as $item)
                                <option value="{{ $item->fgp_item_id }}"
                                    {{ old("in_stock.$i") == $item->fgp_item_id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error("in_stock.$i")
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <select name="orderable[]" class="form-control"
                            style="height: 150px; overflow-y: auto;">
                            @foreach ($orderDetails as $item)
                                <option value="{{ $item->fgp_item_id }}"
                                    {{ old("orderable.$i") == $item->fgp_item_id ? 'selected' : '' }}>
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
