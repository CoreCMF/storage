<?php

namespace CoreCMF\Socialite\Http\Driver;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;

class Qq extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['get_user_info'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://graph.qq.com/oauth2.0/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://graph.qq.com/oauth2.0/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
          'https://graph.qq.com/oauth2.0/me?access_token='.$token
        );
        $this->openId = json_decode(
            $this->removeCallback($response->getBody()->getContents()), true
          )['openid'];
        $response = $this->getHttpClient()->get(
            "https://graph.qq.com/user/get_user_info?access_token=$token&openid={$this->openId}&oauth_consumer_key={$this->clientId}"
        );
        return json_decode($this->removeCallback($response->getBody()->getContents()), true);
    }


    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $this->openId, 'nickname' => $user['nickname'],
            'name' => null, 'email' => null, 'avatar' => $user['figureurl_qq_2'],
        ]);
    }
    /**
     * {@inheritdoc}.
     *
     * @see \Laravel\Socialite\Two\AbstractProvider::getTokenFields()
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
    /**
     * {@inheritdoc}.
     *
     * @see \Laravel\Socialite\Two\AbstractProvider::getAccessToken()
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);
        /*
         * Response content format is "access_token=FE04************************CCE2&expires_in=7776000&refresh_token=88E4************************BE14"
         * Not like "{'access_token':'FE04************************CCE2','expires_in':7776000,'refresh_token':'88E4************************BE14'}"
         * So it can't be decode by json_decode!
        */
        $content = $response->getBody()->getContents();
        parse_str($content, $result);
        return $result;
    }
    /**
     * @param mixed $response
     *
     * @return string
     */
    protected function removeCallback($response)
    {
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }
        return $response;
    }
}
