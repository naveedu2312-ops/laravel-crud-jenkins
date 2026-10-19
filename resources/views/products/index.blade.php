<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laravel Product Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #222;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }

        h1 {
            margin-bottom: 5px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        input,
        textarea {
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        textarea {
            min-height: 80px;
        }

        button {
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            background: #2563eb;
            color: white;
        }

        .delete {
            background: #dc2626;
        }

        .update {
            background: #16a34a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f5f9;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            gap: 8px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Product Management</h1>
    <p class="subtitle">
        Laravel CRUD Application — Docker Frontend, Backend & MySQL
    </p>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error">
            <strong>Please fix the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CREATE -->
    <div class="card">

        <h2>Add Product</h2>

        <form action="/products" method="POST">

            @csrf

            <label>Product Name</label>
            <input
                type="text"
                name="name"
                placeholder="Enter product name"
                required
            >

            <label>Description</label>
            <textarea
                name="description"
                placeholder="Enter product description"
            ></textarea>

            <label>Price</label>
            <input
                type="number"
                step="0.01"
                name="price"
                placeholder="Enter price"
                required
            >

            <button type="submit">
                Add Product
            </button>

        </form>

    </div>


    <!-- READ -->
    <div class="card">

        <h2>Products</h2>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($products as $product)

                    <tr>

                        <td>{{ $product->id }}</td>

                        <td>{{ $product->name }}</td>

                        <td>{{ $product->description }}</td>

                        <td>
                            Rs. {{ number_format($product->price, 2) }}
                        </td>

                        <td>

                            <div class="actions">

                                <!-- UPDATE -->
                                <form
                                    action="/products/{{ $product->id }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="hidden"
                                        name="name"
                                        value="{{ $product->name }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="description"
                                        value="{{ $product->description }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="price"
                                        value="{{ $product->price }}"
                                    >

                                    <button
                                        type="submit"
                                        class="update"
                                    >
                                        Update
                                    </button>

                                </form>


                                <!-- DELETE -->
                                <form
                                    action="/products/{{ $product->id }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="delete"
                                        onclick="return confirm('Delete this product?')"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5">
                            No products found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
