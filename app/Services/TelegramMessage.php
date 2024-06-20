<?php

namespace App\Services;

class TelegramMessage
{
    private $update;

    public function __construct() {}

    public function setUpdate(array $update)
    {
        $this->update = $update;
    }

    public function getMessage(): ?array
    {
        return $this->update['message']?? null;
    }

    public function getMessageId(): int
    {
        return $this->update['message']['message_id'];
    }

    public function getChatId(): int
    {
        return $this->update['message']['chat']['id'];
    }

    public function getChatType(): string
    {
        return $this->update['message']['chat']['type'];
    }

    public function getChatName(): string
    {
        return $this->update['message']['chat']['title'] ?? '';
    }

    public function getUserId(): int
    {
        return $this->update['message']['from']['id'];
    }

    public function getText(): string
    {
        return $this->update['message']['text'];
    }

    public function getFromIsBot(): bool
    {
        return $this->update['message']['from']['is_bot'];
    }

    public function getFirstName(): string
    {
        return $this->update['message']['from']['first_name'];
    }

    public function getLastName(): string
    {
        // there is no last name in thi structure
        return $this->update['message']['from']['last_name'] ?? '';
    }

    public function getUsername(): ?string
    {
        return $this->update['message']['from']['username'] ?? null;
    }

    public function getFromLanguageCode(): string
    {
        return $this->update['message']['from']['language_code'];
    }

    public function getMessageDate(): int
    {
        return $this->update['message']['date'];
    }

    public function getEntities(): array
    {
        return $this->update['message']['entities'] ?? [];
    }

    public function getCaptionEntities(): array
    {
        return $this->update['message']['caption_entities'] ?? [];
    }

    public function getPhoto(): ?array
    {
        return $this->update['message']['photo'] ?? null;
    }

    public function getAudio(): ?array
    {
        return $this->update['message']['audio'] ?? null;
    }

    public function getVideo(): ?array
    {
        return $this->update['message']['video'] ?? null;
    }

    public function getVoice(): ?array
    {
        return $this->update['message']['voice'] ?? null;
    }

    public function getVideoNote(): ?array
    {
        return $this->update['message']['video_note'] ?? null;
    }

    public function getAnimation(): ?array
    {
        return $this->update['message']['animation'] ?? null;
    }

    public function getSticker(): ?array
    {
        return $this->update['message']['sticker'] ?? null;
    }

    public function getContact(): ?array
    {
        return $this->update['message']['contact'] ?? null;
    }

    public function getNewChatMembers(): array
    {
        return $this->update['message']['new_chat_members'] ?? [];
    }

    public function getLeftChatMember(): ?array
    {
        return $this->update['message']['left_chat_member'] ?? null;
    }

    public function getMigrateToChatId(): ?int
    {
        return $this->update['message']['migrate_to_chat_id'] ?? null;
    }

    public function getMigrateFromChatId(): ?int
    {
        return $this->update['message']['migrate_from_chat_id'] ?? null;
    }

    public function getReplyToMessage(): ?array
    {
        return $this->update['message']['reply_to_message'] ?? null;
    }
}
