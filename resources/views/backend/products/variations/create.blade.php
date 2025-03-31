@extends('backend.dashboard')
@section('backend_content')
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">Create Variations for: {{ $product->name }}</h2>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
            <p class="mb-4 text-sm text-gray-600">
                Create variations based on the product's attributes. All combinations will be created automatically.
            </p>

            <h3 class="mb-4 text-lg font-semibold">Available Attributes</h3>
            <div class="mb-6">
                @foreach ($product->attributes as $attribute)
                    <div class="mb-3">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $attribute->name }}</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($attribute->values as $value)
                                <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">
                                    {{ $value->value }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <form id="variationForm" action="{{ route('backend.products.variations.store', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <h3 class="mb-4 text-lg font-semibold">Variation Generator</h3>

                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <h4 class="text-md font-semibold mb-2">Default Values</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                <span>Default Price for All Variations</span>
                                <input type="number" id="default-price" value="{{ $product->base_price }}" step="0.01"
                                    min="0"
                                    class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Will be applied to all variations</p>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                <span>Default Stock Quantity for All Variations</span>
                                <input type="number" id="default-stock" value="100" min="0"
                                    class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Will be applied to all variations</p>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                <span>Default Variation</span>
                                <select id="default-variation"
                                    class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <option value="first">Set First Variation as Default</option>
                                    <option value="none">No Default (Select Manually)</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Which variation should be the default?</p>
                            </label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" id="apply-defaults"
                            class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Apply Default Values
                        </button>
                    </div>
                </div>

                <div id="variations-container">
                    <!-- JavaScript will dynamically generate combinations here -->
                    <div class="text-center py-8 text-gray-500" id="loading-message">
                        Generating combinations...
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Save All Variations
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get product attributes and values
            const attributes = @json($product->attributes->load('values'));
            const defaultPrice = {{ $product->base_price }};
            const defaultStock = 100;

            // Helper function to create array cartesian product
            function cartesianProduct(arrays) {
                return arrays.reduce((acc, array) => {
                    return acc.flatMap(x => array.map(y => [...x, y]));
                }, [
                    []
                ]);
            }

            // Create arrays of attribute values for each attribute
            const attrArrays = [];
            attributes.forEach(attr => {
                const valueArray = attr.values.map(val => {
                    return {
                        attribute_id: attr.id,
                        attribute_name: attr.name,
                        value_id: val.id,
                        value: val.value
                    };
                });
                if (valueArray.length > 0) {
                    attrArrays.push(valueArray);
                }
            });

            // Generate all possible combinations
            const combinations = cartesianProduct(attrArrays);

            // Create HTML for each combination
            const container = document.getElementById('variations-container');
            container.innerHTML = ''; // Remove loading message

            if (combinations.length === 0) {
                container.innerHTML =
                    '<div class="text-center py-8 text-red-500">No combinations can be generated. Please make sure all attributes have values.</div>';
            } else {
                // Set first variation as default by default
                let defaultSet = false;

                combinations.forEach((combination, index) => {
                    const varDiv = document.createElement('div');
                    varDiv.className = 'p-4 border rounded-lg mb-4';
                    varDiv.dataset.index = index;

                    let varHtml = '<div class="mb-3 pb-3 border-b">';

                    // Display the attribute combination
                    combination.forEach(item => {
                        varHtml += `<span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full mr-2">
                                ${item.attribute_name}: ${item.value}
                            </span>`;

                        // Add hidden inputs for the attribute values
                        varHtml +=
                            `<input type="hidden" name="variations[${index}][attribute_values][]" value="${item.value_id}">`;
                    });

                    varHtml += '</div>';

                    // Add fields for variation details
                    varHtml += `
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span>SKU</span>
                                        <input type="text" name="variations[${index}][sku]"
                                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                            placeholder="SKU">
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span>Price</span>
                                        <input type="number" name="variations[${index}][price]" value="${defaultPrice}" step="0.01" min="0"
                                            class="price-input block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                            placeholder="0.00" required>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span>Stock Quantity</span>
                                        <input type="number" name="variations[${index}][stock_quantity]" value="${defaultStock}" min="0"
                                            class="stock-input block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                            placeholder="0" required>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span>Variation Image</span>
                                        <input type="file" name="variations[${index}][image]" accept="image/*"
                                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    </label>
                                </div>
                                <div class="ml-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="variations[${index}][is_default]" value="1"
                                            class="default-checkbox rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                            ${index === 0 && !defaultSet ? 'checked' : ''}>
                                        <span class="ml-2 text-sm text-gray-700">Default Variation</span>
                                    </label>
                                </div>
                            </div>
                        `;

                    varDiv.innerHTML = varHtml;
                    container.appendChild(varDiv);

                    // If this is the first item, mark it as default
                    if (index === 0) {
                        defaultSet = true;
                    }
                });

                // Add a counter showing how many combinations were generated
                const counterDiv = document.createElement('div');
                counterDiv.className = 'mt-4 p-3 bg-gray-50 rounded-lg text-center';
                counterDiv.innerHTML =
                    `<p class="text-sm font-medium text-gray-700">Generated ${combinations.length} variation${combinations.length !== 1 ? 's' : ''}</p>
                     <p class="text-xs text-gray-500 mt-1">At least one variation must be set as default. If none is selected, the first one will automatically be set as default.</p>`;
                container.appendChild(counterDiv);

                // Setup apply defaults button
                document.getElementById('apply-defaults').addEventListener('click', function() {
                    const defaultPrice = document.getElementById('default-price').value;
                    const defaultStock = document.getElementById('default-stock').value;
                    const defaultVariation = document.getElementById('default-variation').value;

                    // Apply price and stock
                    document.querySelectorAll('.price-input').forEach(input => {
                        input.value = defaultPrice;
                    });

                    document.querySelectorAll('.stock-input').forEach(input => {
                        input.value = defaultStock;
                    });

                    // Apply default variation setting
                    const checkboxes = document.querySelectorAll('.default-checkbox');
                    checkboxes.forEach((checkbox, idx) => {
                        if (defaultVariation === 'first') {
                            checkbox.checked = (idx === 0);
                        } else {
                            checkbox.checked = false;
                        }
                    });
                });

                // Ensure only one default variation can be selected
                document.querySelectorAll('.default-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            document.querySelectorAll('.default-checkbox').forEach(cb => {
                                if (cb !== this) {
                                    cb.checked = false;
                                }
                            });
                        }
                    });
                });
            }
        });
    </script>
@endsection
