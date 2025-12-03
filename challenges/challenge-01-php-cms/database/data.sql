USE cms_db;

-- Insert users (passwords are MD5 hashed)
INSERT INTO users (username, password, role) VALUES
('admin', MD5('admin123'), 'admin'),
('user', MD5('user123'), 'user'),
('test', MD5('test123'), 'user');

-- Insert flag
INSERT INTO flags (secret_flag) VALUES
('OSWE{SQLi_FileUpload_PathTraversal_IDOR_Chain_Success!}');

