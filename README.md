# SprintHive

## Group: lbaw2484

### Members:
- **Ana Sofia Silva Pinto** - [up202004606@fc.up.pt](mailto:up202004606@fc.up.pt)
- **Oleksandr Aleshchenko** - [up202210478@up.pt](mailto:up202210478@up.pt)

## Project Vision
**SprintHive** is a tool designed to streamline all communication necessary for a project. It helps manage tasks, tracks progress, and addresses any questions or issues that may arise during the project's lifecycle, providing effective solutions throughout the process. The goal is to improve collaboration and efficiency within project teams, ensuring smoother workflows and better project outcomes.

## Setup and Installation

To run SprintHive using Docker, run the following command:
```bash
docker run -d --name lbaw2483 -p 8001:80 gitlab.up.pt:5050/lbaw/lbaw2425/lbaw2483
```
This command:

- Starts a Docker container named lbaw2483 with the published image (-d runs it in the background)
- Maps port 8001 on your machine to port 80 in the container
- Your application will be available at http://localhost:8001

To stop and remove the container:
```bash
docker stop lbaw2483
docker rm lbaw2483
```

### Credentials for access

Here are some sample credentials for testing purposes:
| Type           | Username | Password    | Mail                |
| -------------- | -------- | ----------- | ------------------- |
| Project Member | alice    | password123 | alice@example.com   |
| Project Leader & Admin | bob      | password456 | bob@example.com     |
| Project Member | charlie  | password789 | charlie@example.com |
| Project Leader | david    | password321 | david@example.com   |
| Project Member | eve      | password654 | eve@example.com     |
| Project Leader | frank    | password987 | frank@example.com   |

These are placeholder credentials for development and testing purposes.

## Contribution

If you would like to contribute to **SprintHive**, please fork the repository and submit a pull request. 

## Contact 

For questions or inquiries, feel free to reach out to the project members ;)

<hr>
> Project developed for Database and Web Applications Laboratory course @FEUP
