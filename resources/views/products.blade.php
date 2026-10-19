<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Products CRUD</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: #2563eb;
            color: white;
            margin-right: 5px;
        }

        button.delete {
            background: #dc2626;
        }

        button.edit {
            background: #f59e0b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8fafc;
        }

        #message {
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Products Management</h1>

    <div class="card">

        <h2 id="form-title">Add Product</h2>

        <input type="hidden" id="product_id">

        <label>Name</label>
        <input type="text" id="name" placeholder="Product name">

        <label>Description</label>
        <textarea id="description" placeholder="Product description"></textarea>

        <label>Price</label>
        <input type="number" id="price" step="0.01" placeholder="Product price">

        <button onclick="saveProduct()">Save Product</button>
        <button onclick="cancelEdit()" id="cancel-button" style="display:none;">
            Cancel
        </button>

    </div>

    <div class="card">

        <h2>Products</h2>

        <div id="message"></div>

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

            <tbody id="products"></tbody>
        </table>

    </div>

</div>

<script>

async function loadProducts() {

    const response = await fetch('/api/products');

    const products = await response.json();

    const tbody = document.getElementById('products');

    tbody.innerHTML = '';

    products.forEach(product => {

        tbody.innerHTML += `
            <tr>
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${product.description ?? ''}</td>
                <td>${product.price}</td>
                <td>
                    <button class="edit"
                        onclick='editProduct(${JSON.stringify(product)})'>
                        Edit
                    </button>

                    <button class="delete"
                        onclick="deleteProduct(${product.id})">
                        Delete
                    </button>
                </td>
            </tr>
        `;

    });
}


async function saveProduct() {

    const id = document.getElementById('product_id').value;

    const data = {
        name: document.getElementById('name').value,
        description: document.getElementById('description').value,
        price: document.getElementById('price').value
    };

    let url = '/api/products';
    let method = 'POST';

    if (id) {
        url = `/api/products/${id}`;
        method = 'PUT';
    }

    const response = await fetch(url, {

        method: method,

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },

        body: JSON.stringify(data)

    });

    if (response.ok) {

        showMessage('Product saved successfully.');

        cancelEdit();

        loadProducts();

    } else {

        const error = await response.json();

        showMessage(
            'Error: ' + JSON.stringify(error)
        );

    }
}


function editProduct(product) {

    document.getElementById('product_id').value = product.id;

    document.getElementById('name').value = product.name;

    document.getElementById('description').value =
        product.description ?? '';

    document.getElementById('price').value = product.price;

    document.getElementById('form-title').innerText =
        'Edit Product';

    document.getElementById('cancel-button').style.display =
        'inline-block';

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}


async function deleteProduct(id) {

    if (!confirm('Are you sure you want to delete this product?')) {
        return;
    }

    const response = await fetch(`/api/products/${id}`, {

        method: 'DELETE',

        headers: {
            'Accept': 'application/json'
        }

    });

    if (response.ok) {

        showMessage('Product deleted successfully.');

        loadProducts();

    } else {

        showMessage('Failed to delete product.');

    }
}


function cancelEdit() {

    document.getElementById('product_id').value = '';

    document.getElementById('name').value = '';

    document.getElementById('description').value = '';

    document.getElementById('price').value = '';

    document.getElementById('form-title').innerText =
        'Add Product';

    document.getElementById('cancel-button').style.display =
        'none';
}


function showMessage(message) {

    const element = document.getElementById('message');

    element.innerText = message;

    setTimeout(() => {
        element.innerText = '';
    }, 3000);
}


loadProducts();

</script>

</body>
</html>
