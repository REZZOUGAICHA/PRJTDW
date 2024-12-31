<?php

class LinkGenerator {
    public static function generateEntityLink($id, $createdAt, $entityType) {
        // Combine id, timestamp, and entity type to create a unique identifier
        $timestamp = strtotime($createdAt);
        $baseString = $id . $timestamp . $entityType;
        
        // Create a hash and take first 12 characters
        $hash = hash('sha256', $baseString);
        $shortHash = substr($hash, 0, 12);
        
        // Combine components into URL-friendly format
        return "/entity/{$entityType}/{$id}-{$shortHash}";
    }

    public static function validateEntityLink($link, $id, $createdAt, $entityType) {
        $expectedLink = self::generateEntityLink($id, $createdAt, $entityType);
        return $link === $expectedLink;
    }
}
