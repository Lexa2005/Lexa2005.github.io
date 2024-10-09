import React, { useState } from 'react';

const VideoCard = ({ thumbnailUrl, title, channel, views }) => {
    const [likes, setLikes] = useState(0);

    const handleLike = () => {
        setLikes(likes + 1);
    };

    return (
        <div className="video-card">
            <a href="#" className="video-thumbnail">
                <img src={thumbnailUrl} alt="Превью видео" />
            </a>
            <div className="video-info">
                <a href="#" className="video-title">{title}</a>
                <div className="video-channel">{channel}</div>
                <div className="video-views">{views}</div>
                <div className="video-likes">
                    <button onClick={handleLike} className="like-button">Лайк</button>
                    <span>{likes} лайков</span>
                </div>
            </div>
        </div>
    );
};

export default VideoCard;