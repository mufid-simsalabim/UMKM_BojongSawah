-- Export Database PostgreSQL untuk Supabase
DROP TABLE IF EXISTS likes CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS posts CASCADE;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS umkm_profiles CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS sessions CASCADE;
DROP TABLE IF EXISTS cache CASCADE;
DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(255) DEFAULT NULL,
  avatar TEXT DEFAULT NULL,
  role VARCHAR(255) NOT NULL DEFAULT 'user',
  status VARCHAR(255) NOT NULL DEFAULT 'approved',
  email_verified_at TIMESTAMP DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE TABLE categories (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE TABLE umkm_profiles (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  nik VARCHAR(255) NOT NULL,
  owner_name VARCHAR(255) NOT NULL,
  store_name VARCHAR(255) NOT NULL,
  phone_wa VARCHAR(255) NOT NULL,
  category VARCHAR(255) NOT NULL DEFAULT 'Kuliner & Olahan',
  address TEXT NOT NULL,
  description TEXT NOT NULL,
  ktp_image TEXT NOT NULL,
  business_image TEXT NOT NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'pending',
  rejection_reason TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE TABLE products (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  category VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price NUMERIC(12,2) NOT NULL,
  unit VARCHAR(50) NOT NULL DEFAULT 'Pcs',
  image TEXT NOT NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE TABLE posts (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  product_id BIGINT DEFAULT NULL REFERENCES products(id) ON DELETE SET NULL,
  content TEXT NOT NULL,
  image TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE TABLE comments (
  id BIGSERIAL PRIMARY KEY,
  post_id BIGINT NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  parent_id BIGINT DEFAULT NULL REFERENCES comments(id) ON DELETE CASCADE,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE TABLE likes (
  id BIGSERIAL PRIMARY KEY,
  post_id BIGINT NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL,
  CONSTRAINT likes_post_id_user_id_unique UNIQUE (post_id, user_id)
);

CREATE TABLE notifications (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  title VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  url VARCHAR(255) DEFAULT NULL,
  is_read BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE TABLE cache (
  key VARCHAR(255) PRIMARY KEY,
  value TEXT NOT NULL,
  expiration INT NOT NULL
);

CREATE TABLE sessions (
  id VARCHAR(255) PRIMARY KEY,
  user_id BIGINT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL,
  payload TEXT NOT NULL,
  last_activity INT NOT NULL
);

-- Initial Data
INSERT INTO users (id, name, email, phone, avatar, role, status, email_verified_at, password, created_at, updated_at) VALUES 
(1, 'Administrator Desa Bojong Sawah', 'admin@bojongsawah.desa.id', '087892264064', NULL, 'admin', 'approved', '2026-08-21 09:16:26', '$2y$12$ifAOWttNnvisbwDZStEy8O8IaGF6dEqlp83A1IBibD33Tj1NKRW4a', '2026-08-21 09:16:26', '2026-08-21 14:43:24');

INSERT INTO categories (id, name, slug, description, created_at, updated_at) VALUES 
(1, 'Kuliner & Olahan', 'kuliner-olahan', 'Sajian makanan, minuman, dan olahan kuliner khas warga Desa Bojong Sawah.', '2026-08-21 15:05:13', '2026-08-21 15:05:13'),
(2, 'Pertanian & Peternakan', 'pertanian-peternakan', 'Hasil bumi, beras organik, sayuran segar, ternak, dan produk pertanian lokal.', '2026-08-21 15:05:13', '2026-08-21 15:05:13'),
(3, 'Kerajinan & Kriya', 'kerajinan-kriya', 'Karya seni, ukiran bambu/kayu, anyaman, dan produk kriya buatan warga.', '2026-08-21 15:05:13', '2026-08-21 15:05:13'),
(4, 'Jasa & Perdagangan', 'jasa-perdagangan', 'Layanan jasa profesi, servis pertukangan, dan perdagangan umum lokal.', '2026-08-21 15:05:13', '2026-08-21 15:05:13'),
(5, 'Lainnya', 'lainnya', 'Kategori produk dan layanan usaha lainnya.', '2026-08-21 15:05:13', '2026-08-21 15:05:13'),
(6, 'eletronik', 'eletronik', NULL, '2026-08-21 15:08:09', '2026-08-21 15:08:09');

SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));
