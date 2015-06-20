<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace conquer\oauth2\models;

use Yii;

/**
 * This is the model class for table "oauth_client".
 *
 * @property string $client_id
 * @property string $client_secret
 * @property string $redirect_uri
 * @property string $grant_type
 * @property string $scope
 * @property integer $user_id
 * @property string $public_key
 *
 * @property AccessToken[] $oauthAccessTokens
 * @property AuthorizationCode[] $oauthAuthorizationCodes
 * @property RefreshToken[] $oauthRefreshTokens
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth2_client}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'client_secret', 'redirect_uri'], 'required'],
            [['scope'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['client_id', 'client_secret', 'grant_type'], 'string', 'max' => 80],
            [['redirect_uri'], 'string', 'max' => 2000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class'=>\yii\behaviors\TimestampBehavior::className()],
            ['class'=>\yii\behaviors\BlameableBehavior::className()],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'client_id' => 'Unique client identifier',
            'client_secret' => 'Client secret',
            'redirect_uri' => 'Redirect URI used for Authorization Grant',
            'grant_type' => 'Space-delimited list of grant types permitted, null = all',
            'scope' => 'Space-delimited list of approved scopes',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOauthAccessTokens()
    {
        return $this->hasMany(OauthAccessToken::className(), ['client_id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOauthAuthorizationCodes()
    {
        return $this->hasMany(OauthAuthorizationCode::className(), ['client_id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOauthRefreshTokens()
    {
        return $this->hasMany(OauthRefreshToken::className(), ['client_id' => 'client_id']);
    }
    
    public function setClientSecret($value)
    {
        $this->client_secret = \Yii::$app->security->generatePasswordHash($value);
    }
}
