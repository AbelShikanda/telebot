

# Telegram Bot Manager

This project is a Telegram bot built with Laravel that allows you to manage groups and private chats.

## Features

- **Group Management**: Easily manage your Telegram groups, including adding/removing members, promoting/demoting admins, and setting group rules.
- **Private Chats**: Engage in private chats with users and provide personalized assistance or support.
- **Command-based Interaction**: Use commands to perform various actions within the bot, such as adding users to groups, sending messages, and more.
- **Persistent Storage**: Store user data, group settings, and chat histories in a database for easy retrieval and management.

## Installation

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/AbelShikanda/telebot.git
   ```

2. **Install Dependencies**:

   ```bash
   cd telegram-bot-manager
   composer install
   ```

3. **Set Up Environment Variables**:

   Rename the `.env.example` file to `.env` and configure your environment variables, including your Telegram bot token.

5. **Start the Bot**:

   ```bash
   php artisan bot:run
   ```

   This command will start the bot and listen for incoming messages and commands.

## Usage

- **Group Management**:
  - `/addmember [user]`: Add a member to the group.
  - `/removemember [user]`: Remove a member from the group.
  - `/promote [user]`: Promote a member to admin.
  - `/demote [user]`: Demote an admin to member.
  - `/setrules [rules]`: Set group rules.
- **Private Chats**:
  - `/start`: Start a private chat with the bot.
  - `/help`: Display help information.
  - `/settings`: View or modify your chat settings.

## Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request with any improvements or feature suggestions.

## License
