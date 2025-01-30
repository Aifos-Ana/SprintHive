DROP SCHEMA IF EXISTS postgres CASCADE;
CREATE SCHEMA IF NOT EXISTS postgres;
SET search_path TO postgres;

DROP TABLE IF EXISTS LikeNotification;
DROP TABLE IF EXISTS CommentNotification;
DROP TABLE IF EXISTS PostComment;
DROP TABLE IF EXISTS TaskNotification;
DROP TABLE IF EXISTS TaskComment;
DROP TABLE IF EXISTS ProjectNotification;
DROP TABLE IF EXISTS ProjectComment;
DROP TABLE IF EXISTS Likes;
DROP TABLE IF EXISTS Notification;
DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Post;
DROP TABLE IF EXISTS Task;
DROP TABLE IF EXISTS FavouriteProject;
DROP TABLE IF EXISTS Project;
DROP TABLE IF EXISTS users;

-- Drop custom types (enums) if they exist
DROP TYPE IF EXISTS role_enum;
DROP TYPE IF EXISTS priority_enum;

-- Enumerations for Users roles and Task priority levels
CREATE TYPE role_enum AS ENUM ('projectMember', 'projectLeader');
CREATE TYPE priority_enum AS ENUM ('Low', 'Medium', 'High');

-- Users Table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profilePic TEXT NOT NULL DEFAULT 'images/default.png', -- public/images/default.png
    role role_enum NOT NULL DEFAULT 'projectMember',
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    remember_token VARCHAR
);

-- Project Table
CREATE TABLE Project (
    projectId SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    startDate DATE,
    endDate DATE,
    id INT REFERENCES Users(id) ON DELETE CASCADE
);

-- UserProject Table (many-to-many relationship between Users and Projects)
CREATE TABLE UserProject (
    userProjectId SERIAL PRIMARY KEY,
    userId INT REFERENCES Users(id) ON DELETE CASCADE,
    projectId INT REFERENCES Project(projectId) ON DELETE CASCADE,
    assignedDate DATE NOT NULL
);

-- FavouriteProject Table (relationship between Users and Project)
CREATE TABLE FavouriteProject (
    favouriteId SERIAL PRIMARY KEY,
    id INT REFERENCES Users(id) ON DELETE CASCADE,
    projectId INT REFERENCES Project(projectId) ON DELETE CASCADE,
    addedDate DATE
);

-- Task Table
CREATE TABLE Task (
    taskId SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority priority_enum NOT NULL,
    dueDate DATE,
    projectId INT REFERENCES Project(projectId) ON DELETE CASCADE
);

-- Post Table
CREATE TABLE Post (
    postId SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    createdAt DATE NOT NULL,
    editedAt DATE,
    projectId INT REFERENCES Project(projectId) ON DELETE CASCADE
);

-- Comment Table
CREATE TABLE Comment (
    commentId SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    createdAt DATE NOT NULL
);

-- Likes Table (renamed from Like)
CREATE TABLE Likes (
    likeId SERIAL PRIMARY KEY,
    createdAt DATE NOT NULL,
    postId INT REFERENCES Post(postId) ON DELETE CASCADE,
    id INT REFERENCES Users(id) ON DELETE CASCADE
);

-- Notification Table
CREATE TABLE Notification (
    notificationId SERIAL PRIMARY KEY,
    message TEXT NOT NULL,
    isRead BOOLEAN DEFAULT FALSE,
    createdAt DATE NOT NULL,
    id INT REFERENCES Users(id) ON DELETE CASCADE
);

-- ProjectComment Table (relationship between Project and Comment)
CREATE TABLE ProjectComment (
    projectCommentId SERIAL PRIMARY KEY,
    projectId INT REFERENCES Project(projectId) ON DELETE CASCADE,
    commentId INT REFERENCES Comment(commentId) ON DELETE CASCADE
);

-- ProjectNotification Table (relationship between Project and Notification)
CREATE TABLE ProjectNotification (
    projectNotificationId SERIAL PRIMARY KEY,
    projectId INT REFERENCES Project(projectId) ON DELETE CASCADE,
    notificationId INT REFERENCES Notification(notificationId) ON DELETE CASCADE
);

-- TaskComment Table (relationship between Task and Comment)
CREATE TABLE TaskComment (
    taskCommentId SERIAL PRIMARY KEY,
    taskId INT REFERENCES Task(taskId) ON DELETE CASCADE,
    commentId INT REFERENCES Comment(commentId) ON DELETE CASCADE
);

-- TaskNotification Table (relationship between Task and Notification)
CREATE TABLE TaskNotification (
    taskNotificationId SERIAL PRIMARY KEY,
    taskId INT REFERENCES Task(taskId) ON DELETE CASCADE,
    notificationId INT REFERENCES Notification(notificationId) ON DELETE CASCADE
);

-- PostComment Table (relationship between Post and Comment)
CREATE TABLE PostComment (
    postCommentId SERIAL PRIMARY KEY,
    postId INT REFERENCES Post(postId) ON DELETE CASCADE,
    commentId INT REFERENCES Comment(commentId) ON DELETE CASCADE
);

-- CommentNotification Table (relationship between Comment and Notification)
CREATE TABLE CommentNotification (
    commentNotificationId SERIAL PRIMARY KEY,
    commentId INT REFERENCES Comment(commentId) ON DELETE CASCADE,
    notificationId INT REFERENCES Notification(notificationId) ON DELETE CASCADE
);

-- LikeNotification Table (relationship between Likes and Notification)
CREATE TABLE LikeNotification (
    likeNotificationId SERIAL PRIMARY KEY,
    likeId INT REFERENCES Likes(likeId) ON DELETE CASCADE,
    notificationId INT REFERENCES Notification(notificationId) ON DELETE CASCADE
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (email)
);


-- INDEXES:
-- IDX01: Index on Project.id
DROP INDEX IF EXISTS IDX01;
CREATE INDEX IDX01 ON Project USING btree(id);

-- IDX02: Index on Notification.id
DROP INDEX IF EXISTS IDX02;
CREATE INDEX IDX02 ON Notification USING btree(id);

-- IDX03: Index on Task.projectId
DROP INDEX IF EXISTS IDX03;
CREATE INDEX IDX03 ON Task USING btree(projectId);

-- IDX04: Index on Post.projectId
DROP INDEX IF EXISTS IDX04;
CREATE INDEX IDX04 ON Post USING btree(projectId);

-- IDX05: Clustered index on Comment.createdAt
DROP INDEX IF EXISTS IDX05;
CREATE INDEX IDX05 ON Comment USING btree(createdAt);
CLUSTER Comment USING IDX05;

-- IDX06: Index on Likes.postId
DROP INDEX IF EXISTS IDX06;
CREATE INDEX IDX06 ON Likes USING btree(postId);

-- FTS:
-- IDX07:
-- Add tsvector column to store processed username data.
ALTER TABLE users ADD COLUMN name_tsv tsvector;

-- Create a function to update the tsvector on INSERT or UPDATE.
CREATE FUNCTION users_search_update() RETURNS TRIGGER AS $$
BEGIN
    NEW.name_tsv = to_tsvector('english', NEW.name);
    RETURN NEW;
END $$ LANGUAGE plpgsql;

-- Create a trigger to execute the function before insert or update.
CREATE TRIGGER users_search_update
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION users_search_update();

-- Create the GIN index on the tsvector column.
CREATE INDEX IDX07 ON users USING GIN (name_tsv);

-- IDX08
-- Add tsvector column to store processed name data.
ALTER TABLE Project ADD COLUMN name_tsv tsvector;

-- Create a function to update the tsvector on INSERT or UPDATE.
CREATE FUNCTION project_search_update() RETURNS TRIGGER AS $$
BEGIN
    NEW.name_tsv = to_tsvector('english', NEW.name);
    RETURN NEW;
END $$ LANGUAGE plpgsql;

-- Create a trigger to execute the function before insert or update.
CREATE TRIGGER project_search_update
    BEFORE INSERT OR UPDATE ON Project
    FOR EACH ROW
    EXECUTE FUNCTION project_search_update();

-- Create the GIN index on the tsvector column.
CREATE INDEX IDX08 ON Project USING GIN (name_tsv);

-- NEW FTS:
-- Add tsvector column to store processed title and description data
ALTER TABLE Task ADD COLUMN task_tsv tsvector;

-- Create a function to update the tsvector on INSERT or UPDATE
CREATE FUNCTION task_search_update() RETURNS TRIGGER AS $$
BEGIN
    NEW.task_tsv = to_tsvector('english', COALESCE(NEW.title, '') || ' ' || COALESCE(NEW.description, ''));
    RETURN NEW;
END $$ LANGUAGE plpgsql;

-- Create a trigger to execute the function before insert or update
CREATE TRIGGER task_search_update
    BEFORE INSERT OR UPDATE ON Task
    FOR EACH ROW
    EXECUTE FUNCTION task_search_update();

-- Create the GIN index on the tsvector column
CREATE INDEX task_tsv_idx ON Task USING GIN (task_tsv);
-- END OF NEW FTS

-- POPULATE DATA
-- Insert sample data for Users
INSERT INTO users (name, email, password, role) VALUES
('alice', 'alice@example.com', '$2y$10$cmhfTPfcs1dZZYnnbvr78upCbg7ndptO4hh6BW0GjoFRCCZNYioCi', 'projectMember'), --password123
('bob', 'bob@example.com', '$2y$10$MG3NmxhEmjDtp8ryhAeMnuYyB.NcoEj37J61dUqboWmshhTD2vpRS', 'projectLeader'), --password456
('charlie', 'charlie@example.com', '$2y$10$PtxQcMRrFv.WvPkgTCG/xuaWIU5JPIa29CxfUsjC7t7d3GAY/Upd6', 'projectMember'), --password789
('david', 'david@example.com', '$2y$10$6Kn8k0Zj6P.QHfCxbr1Ta.M364gwaVtc3AdJm5Us5VfgNfUdaehpu', 'projectLeader'), --password321
('eve', 'eve@example.com', '$2y$10$8w8EUR4V1CtedbROooCkEOWntzuIoDrhkyIwICCCK/MSL9PcQ5ySq', 'projectMember'), --password654
('frank', 'frank@example.com', '$2y$10$7e7VI4QZO/NCytURGMcm7usANndnYwQOalLxT6q1TnFIvejztYQNa', 'projectMember'); --password987

UPDATE users
SET is_admin = TRUE
WHERE email = 'bob@example.com';

-- Populating Projects
INSERT INTO Project (name, description, startDate, endDate, id) VALUES
('Website Redesign', 'Redesign the corporate website', '2024-01-01', '2024-06-01', 2), -- Bob (Project Leader)
('Mobile App Development', 'Develop a new mobile app', '2024-02-01', '2024-08-01', 4), -- David (Project Leader)
('E-commerce Platform', 'Build a new e-commerce platform', '2024-03-01', '2024-12-01', 2), -- Bob (Project Leader)
('AI Research', 'Conduct research on AI technologies', '2024-04-01', '2025-04-01', 6); -- Frank

-- Associate Users with Projects in UserProject Table
INSERT INTO UserProject (userId, projectId, assignedDate) VALUES
-- Website Redesign
(1, 1, '2024-01-01'), -- Alice
(2, 1, '2024-01-01'), -- Bob
(3, 1, '2024-01-01'), -- Charlie
-- Mobile App Development
(4, 2, '2024-02-01'), -- David
(5, 2, '2024-02-01'), -- Eve
(1, 2, '2024-02-01'), -- Alice
-- E-commerce Platform
(2, 3, '2024-03-01'), -- Bob
(3, 3, '2024-03-01'), -- Charlie
(4, 3, '2024-03-01'), -- David
-- AI Research
(6, 4, '2024-04-01'), -- Frank
(2, 4, '2024-04-01'), -- Bob
(3, 4, '2024-04-01'); -- Charlie


-- Populating Favourite Projects
INSERT INTO FavouriteProject (id, projectId, addedDate) VALUES
(1, 1, '2024-01-10'), -- Alice favors Website Redesign
(3, 3, '2024-02-15'), -- Charlie favors E-commerce Platform
(6, 4, '2024-04-10'); -- Frank favors AI Research

-- Populating Tasks
INSERT INTO Task (title, description, priority, dueDate, projectId) VALUES
('Design Homepage', 'Create the homepage design', 'High', '2024-03-01', 1),
('Implement Authentication', 'Set up user login system', 'Medium', '2024-04-01', 2),
('Database Optimization', 'Improve database performance', 'Low', '2024-05-01', 1),
('Develop Payment Gateway', 'Integrate payment processing', 'High', '2024-06-15', 3),
('Optimize AI Model', 'Enhance model accuracy', 'Medium', '2025-01-10', 4),
('Setup CI/CD Pipeline', 'Implement continuous integration', 'High', '2024-07-01', 3);

-- Populating Posts
INSERT INTO Post (content, createdAt, editedAt, projectId) VALUES
('Kickoff meeting was successful!', '2024-01-05', NULL, 1),
('Wireframes are ready for review', '2024-01-20', '2024-01-25', 1),
('Testing phase started', '2024-06-01', NULL, 2),
('Initial research papers reviewed', '2024-04-15', NULL, 4),
('Backend architecture finalized', '2024-03-20', '2024-03-25', 3),
('Project goals aligned', '2024-02-15', NULL, 2);

-- Populating Comments
INSERT INTO Comment (content, createdAt) VALUES
('Great work on the homepage design!', '2024-03-02'),
('I have some suggestions for the login feature', '2024-04-02'),
('The database performance has improved', '2024-05-02'),
('Looking forward to seeing the final product!', '2024-03-06'),
('The model needs more training data', '2024-04-16'),
('Excellent progress on the CI/CD pipeline', '2024-07-02');

-- Populating Likes
INSERT INTO Likes (createdAt, postId, id) VALUES
('2024-01-06', 1, 1),
('2024-01-21', 2, 3),
('2024-06-02', 3, 2),
('2024-03-07', 4, 2),
('2024-04-16', 5, 3),
('2024-02-16', 6, 1);

-- Populating Notifications
INSERT INTO Notification (message, isRead, createdAt, id) VALUES
('You have a new comment on your post', FALSE, '2024-01-07', 1),
('A task has been assigned to you', TRUE, '2024-01-08', 2),
('Your project has been updated', FALSE, '2024-06-03', 3),
('New task has been assigned to you', FALSE, '2024-03-06', 4),
('A post has been liked', TRUE, '2024-04-17', 5),
('Your comment received a reply', FALSE, '2024-07-03', 6);

-- Populating Relationships
INSERT INTO ProjectComment (projectId, commentId) VALUES
(1, 1), (2, 2), (3, 3), (4, 4);

INSERT INTO ProjectNotification (projectId, notificationId) VALUES
(1, 1), (2, 2), (3, 3), (4, 4);

INSERT INTO TaskComment (taskId, commentId) VALUES
(1, 1), (2, 2), (4, 5), (6, 6);

INSERT INTO TaskNotification (taskId, notificationId) VALUES
(1, 1), (3, 3), (4, 4), (6, 6);

INSERT INTO PostComment (postId, commentId) VALUES
(1, 1), (2, 2), (4, 3), (5, 4);

INSERT INTO CommentNotification (commentId, notificationId) VALUES
(1, 1), (2, 2), (3, 3), (4, 4);

INSERT INTO LikeNotification (likeId, notificationId) VALUES
(1, 1), (2, 2), (4, 3), (5, 4);
