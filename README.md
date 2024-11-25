
# Time Tracker

An application to track tasks and time effortlessly and efficiently. Perfect for keeping a detailed record of daily activities and optimizing productivity.


## Authors

- [@anunde](https://github.com/anunde)


## Run Locally

You need to have installed docker.

Clone the project

```bash
  git clone https://link-to-project
```

Go to the project directory

```bash
  cd time-tracker
```

Build the containers

```bash
  make build
```

Start the containers

```bash
  make run
```

Install composer dependencies

```bash
  make prepare
```

You can access throught: http://localhost:300


## Tech Stack

**Client:** Twig, Boostrap, JavaScript

**Server:** PHP, Symfony


## Features

- Start/stop timers
- View summary from the work day
- Console functionality.


## API Reference

#### Get all tasks

```http
   GET /tasks
```

| Parameter | Type     | Description                   |
| :-------- | :------- | :---------------------------- |
| None      |          | Retrieves a list of all tasks |


#### Start a timer

```http
  POST /timer/start
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | Required. Name of the task to start a timer for |


### Stop a timer

```http
  POST /timer/stop
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | Required. Name of the task to stop an actived timer |




## Usage/Examples

You can also use the terminal for reaching the same funcionality.

#### Start/End timer

```bash
php bin/console app:task:action <action> <name>
```

#### List tasks

```bash
php bin/console app:task:list
```

## License

[MIT](https://choosealicense.com/licenses/mit/)

