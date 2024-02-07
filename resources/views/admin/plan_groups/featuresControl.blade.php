@if ($type == 'boolean')
    <tr>
        <th class="fw-normal ps-0" valign="middle">
            {{ trans('messages.plan.feature.' . $name) }}
        </th>
        <td>
            <label class="checker ms-2">
                <input type="hidden" name="features[{{ $name }}]" value="0">
                <input {{ $planGroup->getFeature($name) ? " checked" : "" }} type="checkbox"
                    name="features[{{ $name }}]" value="1" class="styled4">
                <span class="checker-symbol"></span>
            </label>
        </td>
    </tr>
@elseif($type == 'number')
    <tr>
        <th class="fw-normal ps-0" valign="middle">
            {{ trans('messages.plan.feature.' . $name) }}
        </th>
        <td>
            <input style="width:100px" type="number" name="features[{{ $name }}]" value="{{ $planGroup->getFeature($name) }}"
                class="form-control form-control-sm rd-10 {{ $errors->has($name) ? 'is-invalid' : '' }}"
            />
        </td>
    </tr>
@endif