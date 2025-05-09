CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TABLE IF NOT EXISTS users (
	id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
	username VARCHAR(50) NOT NULL UNIQUE,
	email VARCHAR(100) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tokens (
	id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
	verifToken UUID DEFAULT uuid_generate_v4(),
	verified BOOLEAN DEFAULT FALSE,
	userID UUID NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pictures (
	id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
	authorID UUID NOT NULL,
	likes INT DEFAULT 0,
	photo_url VARCHAR(511) UNIQUE,
	description VARCHAR(511),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS likes (
	id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
	authorID UUID NOT NULL,
	picture UUID NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS 	comments (
	id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
	authorID UUID NOT NULL,
	picture UUID NOT NULL,
	content VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password)
SELECT 'twinters', 'tom.wintersheim@gmail.com', '$2a$12$Z9GGPOJDvppLWIJVLRF1VOA0wddLwddotTquhcWn2bDbr/xAeuTka'
WHERE NOT EXISTS (
  SELECT 1 FROM users WHERE username = 'twinters'
);
INSERT INTO tokens (userID, verified) SELECT id, TRUE FROM users WHERE username = 'twinters';
