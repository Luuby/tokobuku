<?php
// Fungsi utama untuk ambil data buku dengan filter, sort, pagination
function get_books($conn, $search = '', $category = 0, $sort = 'title_asc', $page = 1, $items_per_page = 8) {
    // Validasi sort
    $valid_sorts = ['title_asc', 'title_desc', 'price_asc', 'price_desc'];
    if (!in_array($sort, $valid_sorts)) {
        $sort = 'title_asc';
    }

    // Query dasar
    $query = "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.stock > 0";
    $params = [];
    $types = "";

    // Filter pencarian
    if (!empty($search)) {
        $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.description LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term]);
        $types .= "sss";
    }

    // Filter kategori
    if ($category > 0) {
        $query .= " AND b.category_id = ?";
        $params[] = $category;
        $types .= "i";
    }

    // Order by
    switch ($sort) {
        case 'title_asc': $query .= " ORDER BY b.title ASC"; break;
        case 'title_desc': $query .= " ORDER BY b.title DESC"; break;
        case 'price_asc': $query .= " ORDER BY b.price ASC"; break;
        case 'price_desc': $query .= " ORDER BY b.price DESC"; break;
        default: $query .= " ORDER BY b.title ASC"; break;
    }

    // Hitung total item
    $count_query = str_replace("b.*, c.name as category_name", "COUNT(*) as total", $query);
    $stmt_count = $conn->prepare($count_query);
    if (!empty($params)) {
        $stmt_count->bind_param($types, ...$params);
    }
    $stmt_count->execute();
    $total_items = $stmt_count->get_result()->fetch_assoc()['total'];
    $stmt_count->close();

    $total_pages = ceil($total_items / $items_per_page);
    $offset = ($page - 1) * $items_per_page;

    // Query utama dengan limit
    $query .= " LIMIT ? OFFSET ?";
    $params[] = $items_per_page;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    return [
        'books' => $result,
        'total_pages' => $total_pages,
        'current_page' => $page,
        'total_items' => $total_items
    ];
}

// Fungsi ambil nama kategori
function get_category_name($conn, $category_id) {
    if ($category_id <= 0) return '';
    $stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['name'] ?? '';
}

// Fungsi ambil semua kategori
function get_categories($conn) {
    return $conn->query("SELECT * FROM categories ORDER BY name");
}
?>