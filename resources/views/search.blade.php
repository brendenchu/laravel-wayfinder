<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* Global styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f8fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Form styles */
        .search-form {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px 15px;
        }
        .form-group {
            flex: 1 0 200px;
            padding: 0 10px;
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 14px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        select {
            height: 38px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 30px;
        }
        .btn {
            background-color: #3490dc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #2779bd;
        }
        .btn-reset {
            background-color: #6c757d;
            margin-left: 10px;
        }
        .btn-reset:hover {
            background-color: #5a6268;
        }
        .form-buttons {
            margin-top: 10px;
            text-align: right;
        }

        /* Table styles */
        .results-table {
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f8fafc;
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e2e8f0;
            font-size: 14px;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover {
            background-color: #f8fafc;
        }
        .empty-results {
            padding: 30px;
            text-align: center;
            color: #6c757d;
        }

        /* Status and count */
        .results-status {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
            color: #6c757d;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 30px 0 0;
        }
        .pagination li {
            margin: 0 2px;
        }
        .pagination li a, .pagination li span {
            display: block;
            padding: 8px 12px;
            color: #3490dc;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .pagination li a:hover {
            background-color: #e9f2fa;
        }
        .pagination li.active span {
            background-color: #3490dc;
            color: white;
        }
        .pagination li.disabled span {
            color: #aaa;
            cursor: not-allowed;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            .form-group {
                flex: 0 0 100%;
            }
            .form-buttons {
                text-align: center;
            }
            .results-table {
                overflow-x: auto;
            }
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Products Search</h1>

        <!-- Search Form -->
        <div class="search-form">
            <form action="/products/search" method="GET">
                <div class="form-row">
                    <div class="form-group">
                        <label for="keyword">Keyword</label>
                        <input type="text" id="keyword" name="keyword" value="smartphone" placeholder="Search by name or description">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">All Categories</option>
                            <option value="1" selected>Electronics</option>
                            <option value="2">Clothing</option>
                            <option value="3">Home & Kitchen</option>
                            <option value="4">Books</option>
                            <option value="5">Sports</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price_min">Min Price</label>
                        <input type="number" id="price_min" name="price_min" value="200" placeholder="Min Price" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="price_max">Max Price</label>
                        <input type="number" id="price_max" name="price_max" value="1500" placeholder="Max Price" min="0" step="0.01">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="in_stock" selected>In Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                            <option value="discontinued">Discontinued</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sort_by">Sort By</label>
                        <select id="sort_by" name="sort_by">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="price_low" selected>Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn">Search</button>
                    <button type="button" class="btn btn-reset" id="resetButton">Reset</button>
                </div>
            </form>
        </div>

        <!-- Results Status -->
        <div class="results-status">
            <span>Showing 1 - 10 of 24 results</span>
            <span>Filtered by: Keyword, Category, Price, Status</span>
        </div>

        <!-- Results Table -->
        <div class="results-table">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>101</td>
                    <td>Budget Smartphone X</td>
                    <td>Electronics</td>
                    <td>$249.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Mar 12, 2025</td>
                    <td>
                        <a href="/products/101" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/101/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>115</td>
                    <td>Premium Smartphone Y</td>
                    <td>Electronics</td>
                    <td>$499.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Mar 10, 2025</td>
                    <td>
                        <a href="/products/115" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/115/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>124</td>
                    <td>Gaming Smartphone Z</td>
                    <td>Electronics</td>
                    <td>$699.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Mar 08, 2025</td>
                    <td>
                        <a href="/products/124" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/124/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>132</td>
                    <td>Foldable Smartphone Pro</td>
                    <td>Electronics</td>
                    <td>$999.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Mar 05, 2025</td>
                    <td>
                        <a href="/products/132" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/132/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>145</td>
                    <td>Camera Smartphone Ultra</td>
                    <td>Electronics</td>
                    <td>$799.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Mar 03, 2025</td>
                    <td>
                        <a href="/products/145" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/145/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>152</td>
                    <td>Rugged Smartphone Outdoor</td>
                    <td>Electronics</td>
                    <td>$399.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Feb 28, 2025</td>
                    <td>
                        <a href="/products/152" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/152/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>163</td>
                    <td>Mini Smartphone Lite</td>
                    <td>Electronics</td>
                    <td>$299.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Feb 25, 2025</td>
                    <td>
                        <a href="/products/163" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/163/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>178</td>
                    <td>Business Smartphone Elite</td>
                    <td>Electronics</td>
                    <td>$849.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Feb 22, 2025</td>
                    <td>
                        <a href="/products/178" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/178/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>182</td>
                    <td>AI Smartphone Assistant</td>
                    <td>Electronics</td>
                    <td>$649.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Feb 20, 2025</td>
                    <td>
                        <a href="/products/182" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/182/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td>197</td>
                    <td>5G Smartphone Connect</td>
                    <td>Electronics</td>
                    <td>$549.99</td>
                    <td><span style="color: green;">In Stock</span></td>
                    <td>Feb 18, 2025</td>
                    <td>
                        <a href="/products/197" style="color: #3490dc; text-decoration: none; margin-right: 10px;">View</a>
                        <a href="/products/197/edit" style="color: #3490dc; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <ul class="pagination">
                <li class="disabled"><span>&laquo;</span></li>
                <li class="active"><span>1</span></li>
                <li><a href="/products/search?page=2&keyword=smartphone&category=1&price_min=200&price_max=1500&status=in_stock&sort_by=price_low">2</a></li>
                <li><a href="/products/search?page=3&keyword=smartphone&category=1&price_min=200&price_max=1500&status=in_stock&sort_by=price_low">3</a></li>
                <li><a href="/products/search?page=2&keyword=smartphone&category=1&price_min=200&price_max=1500&status=in_stock&sort_by=price_low">&raquo;</a></li>
            </ul>
        </div>
    </div>
    <script>
        // disable submit button
        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.addEventListener('click', function(event) {
            event.preventDefault();
            alert('This button is disabled.');
        });

        // disable links
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                alert('This link is disabled.');
            });
        });

        // clear form inputs
        const resetButton = document.getElementById('resetButton');
        resetButton.addEventListener('click', function() {
            const formInputs = document.querySelectorAll('input[type="text"], input[type="number"], select');
            formInputs.forEach(function(input) {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });
            document.getElementById('keyword').focus();
        });
    </script>
</body>
</html>
