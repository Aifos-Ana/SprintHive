SET search_path TO lbaw2483;

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
DROP TABLE IF EXISTS Users;

-- Drop custom types (enums) if they exist
DROP TYPE IF EXISTS role_enum;
DROP TYPE IF EXISTS priority_enum;

-- Enumerations for Users roles and Task priority levels
CREATE TYPE role_enum AS ENUM ('projectMember', 'projectLeader');
CREATE TYPE priority_enum AS ENUM ('low', 'medium', 'high');

-- Users Table
CREATE TABLE Users (
    userId SERIAL PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role role_enum NOT NULL
);

-- Project Table
CREATE TABLE Project (
    projectId SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    startDate DATE,
    endDate DATE,
    userId INT REFERENCES Users(userId) ON DELETE CASCADE
);

-- FavouriteProject Table (relationship between Users and Project)
CREATE TABLE FavouriteProject (
    favouriteId SERIAL PRIMARY KEY,
    userId INT REFERENCES Users(userId) ON DELETE CASCADE,
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
    userId INT REFERENCES Users(userId) ON DELETE CASCADE
);

-- Notification Table
CREATE TABLE Notification (
    notificationId SERIAL PRIMARY KEY,
    message TEXT NOT NULL,
    isRead BOOLEAN DEFAULT FALSE,
    createdAt DATE NOT NULL,
    userId INT REFERENCES Users(userId) ON DELETE CASCADE
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

-- INDEXES:
-- IDX01: Index on Project.userId
DROP INDEX IF EXISTS IDX01;
CREATE INDEX IDX01 ON Project USING btree(userId);

-- IDX02: Index on Notification.userId
DROP INDEX IF EXISTS IDX02;
CREATE INDEX IDX02 ON Notification USING btree(userId);

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
ALTER TABLE Users ADD COLUMN username_tsv tsvector;

-- Create a function to update the tsvector on INSERT or UPDATE.
CREATE FUNCTION users_search_update() RETURNS TRIGGER AS $$
BEGIN
    NEW.username_tsv = to_tsvector('english', NEW.username);
    RETURN NEW;
END $$ LANGUAGE plpgsql;

-- Create a trigger to execute the function before insert or update.
CREATE TRIGGER users_search_update
    BEFORE INSERT OR UPDATE ON Users
    FOR EACH ROW
    EXECUTE FUNCTION users_search_update();

-- Create the GIN index on the tsvector column.
CREATE INDEX IDX07 ON Users USING GIN (username_tsv);

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

