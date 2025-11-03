<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $form->name }}</h1>
            
            @if($form->description)
                <p class="text-gray-600 mb-6">{{ $form->description }}</p>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact-form.submit', $form->slug) }}" class="space-y-6">
                @csrf
                
                @foreach($form->fields as $field)
                    <div>
                        <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $field['label'] }}
                            @if($field['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($field['type'] === 'textarea')
                            <textarea
                                id="{{ $field['name'] }}"
                                name="{{ $field['name'] }}"
                                rows="4"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                {{ ($field['required'] ?? false) ? 'required' : '' }}
                            >{{ old($field['name']) }}</textarea>

                        @elseif($field['type'] === 'select')
                            <select
                                id="{{ $field['name'] }}"
                                name="{{ $field['name'] }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                {{ ($field['required'] ?? false) ? 'required' : '' }}
                            >
                                <option value="">Select an option</option>
                                @foreach(explode("\n", $field['options'] ?? '') as $option)
                                    @if(trim($option))
                                        <option value="{{ trim($option) }}" {{ old($field['name']) == trim($option) ? 'selected' : '' }}>
                                            {{ trim($option) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                        @elseif($field['type'] === 'checkbox')
                            <div class="space-y-2">
                                @foreach(explode("\n", $field['options'] ?? '') as $option)
                                    @if(trim($option))
                                        <label class="flex items-center">
                                            <input
                                                type="checkbox"
                                                name="{{ $field['name'] }}[]"
                                                value="{{ trim($option) }}"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                {{ is_array(old($field['name'])) && in_array(trim($option), old($field['name'])) ? 'checked' : '' }}
                                            >
                                            <span class="ml-2 text-gray-700">{{ trim($option) }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>

                        @else
                            <input
                                type="{{ $field['type'] }}"
                                id="{{ $field['name'] }}"
                                name="{{ $field['name'] }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                value="{{ old($field['name']) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                {{ ($field['required'] ?? false) ? 'required' : '' }}
                            >
                        @endif
                    </div>
                @endforeach

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition duration-200"
                >
                    {{ $form->submit_button_text }}
                </button>
            </form>
        </div>
    </div>
</body>
</html>