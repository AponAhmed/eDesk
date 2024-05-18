<?php

namespace App\Http\Traits;

trait MessageTraits
{


    /**
     * Add a label to the 'labels' field.
     *
     * @param string $label
     * @return void
     */
    public function addLabel($label)
    {
        $labels = $this->getLabels();
        $labels[] = $label;
        $this->updateLabels($labels);
        return $this;
    }



    /**
     * Remove a label from the 'labels' field.
     *
     * @param string $label
     * @return void
     */
    public function removeLabel($label)
    {
        $labels = $this->getLabels();

        $labels = array_diff($labels, [$label]);
        $this->updateLabels($labels);
        return $this;
    }

    /**
     * Get the 'labels' field as an array.
     *
     * @return array
     */
    public function getLabels()
    {
        $labels = $this->labels ? explode(',', $this->labels) : [];
        return array_filter(array_unique($labels));
    }

    /**
     * Update the 'labels' field with the given array of labels.
     *
     * @param array $labels
     * @return void
     */
    public function updateLabels(array $labels)
    {
        $this->update(['labels' => implode(',', $labels)]);
    }


    /**
     * Create a new message.
     *
     * @param array $data
     * @return \App\Message
     */
    public static function createMessage($data)
    {
        return self::create($data);
    }
    public function snippet($length = 100)
    {
        // Remove CSS styles from the message content
        $messageWithoutCss = preg_replace('/<style\s*[^>]*>.*?<\/style>/is', '', $this->message);

        // Get the message content and trim it to the specified length
        $snippet = strip_tags($messageWithoutCss);
        $snippet = html_entity_decode($snippet, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove any non-word and non-whitespace characters
        $snippet = preg_replace('/[^\w\s]/u', '', $snippet);

        // Remove multiple whitespaces
        $snippet = trim(preg_replace('/\s+/u', ' ', $snippet));
        // If the message is longer than the snippet, trim it to the specified length
        if (mb_strlen($snippet) > $length) {
            $snippet = mb_substr($snippet, 0, $length);
            // Trim last word to avoid cutting it in half
            $snippet = preg_replace('/\s+?(\S+)?$/', '', $snippet);
            $snippet .= '...';
        }

        return $snippet;
    }
}
