<?php

namespace Bengr\Admin\Pages;

use Bengr\Admin\Actions\Action;
use Illuminate\Contracts\Support\Responsable;

class PageResponse implements Responsable
{
    protected array $content;

    final public function __construct($content = '')
    {
        if (!is_array($content)) {
            $this->content = [
                'message' => $content,
            ];
        } else {
            $this->content = $content;
        }
    }

    public static function make($content = ''): static
    {
        return app(static::class, ['content' => $content]);
    }

    public function toResponse($request)
    {

        return response()->json($this->content);
    }

    public function redirect(string $pageClass, array $params = []): self
    {
        $action = Action::make()->redirect($pageClass, $params);

        $this->content['redirect'] = [
            'name' => $action->getRedirectName(),
            'url' => $action->getRedirectUrl(),
        ];

        return $this;
    }
}
