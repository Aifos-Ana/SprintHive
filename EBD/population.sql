SET search_path TO lbaw2483;

-- POPULATE DATA
-- Insert sample data for Users
INSERT INTO Users (username, email, password, role) VALUES
('alice', 'alice@example.com', 'password123', 'projectMember'),
('bob', 'bob@example.com', 'password456', 'projectLeader'),
('charlie', 'charlie@example.com', 'password789', 'projectMember');

-- Insert sample data for Project
INSERT INTO Project (name, description, startDate, endDate, userId) VALUES
('Website Redesign', 'Redesign the corporate website', '2024-01-01', '2024-06-01', 2),
('Mobile App Development', 'Develop a new mobile app', '2024-02-01', '2024-08-01', 2);

-- Insert sample data for FavouriteProject
INSERT INTO FavouriteProject (userId, projectId, addedDate) VALUES
(1, 1, '2024-01-10'),
(1, 2, '2024-01-15'),
(3, 1, '2024-01-20');

-- Insert sample data for Task
INSERT INTO Task (title, description, priority, dueDate, projectId) VALUES
('Design Homepage', 'Create the homepage design', 'high', '2024-03-01', 1),
('Implement Authentication', 'Set up user login system', 'medium', '2024-04-01', 2),
('Database Optimization', 'Improve database performance', 'low', '2024-05-01', 1);

-- Insert sample data for Post
INSERT INTO Post (content, createdAt, editedAt, projectId) VALUES
('Kickoff meeting was successful!', '2024-01-05', NULL, 1),
('Wireframes are ready for review', '2024-01-20', '2024-01-25', 1),
('Testing phase started', '2024-06-01', NULL, 2);

-- Insert sample data for Comment
INSERT INTO Comment (content, createdAt) VALUES
('Great work on the homepage design!', '2024-03-02'),
('I have some suggestions for the login feature', '2024-04-02'),
('The database performance has improved', '2024-05-02');

-- Insert sample data for Likes
INSERT INTO Likes (createdAt, postId, userId) VALUES
('2024-01-06', 1, 1),
('2024-01-21', 2, 3),
('2024-06-02', 3, 2);

-- Insert sample data for Notification
INSERT INTO Notification (message, isRead, createdAt, userId) VALUES
('You have a new comment on your post', FALSE, '2024-01-07', 1),
('A task has been assigned to you', TRUE, '2024-01-08', 2),
('Your project has been updated', FALSE, '2024-06-03', 3);

-- Relationship tables
INSERT INTO ProjectComment (projectId, commentId) VALUES (1, 1), (2, 2);
INSERT INTO ProjectNotification (projectId, notificationId) VALUES (1, 1), (2, 2);
INSERT INTO TaskComment (taskId, commentId) VALUES (1, 1), (2, 2);
INSERT INTO TaskNotification (taskId, notificationId) VALUES (1, 1), (3, 3);
INSERT INTO PostComment (postId, commentId) VALUES (1, 1), (2, 2);
INSERT INTO CommentNotification (commentId, notificationId) VALUES (1, 1), (2, 2);
INSERT INTO LikeNotification (likeId, notificationId) VALUES (1, 1), (2, 2);
