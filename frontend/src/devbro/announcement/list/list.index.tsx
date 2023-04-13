import { APIPath, RoutePath } from 'data';
import React, { useEffect, useState } from 'react';
import { FaSpinner } from 'react-icons/fa';
import { Link } from 'react-router-dom';
import { RestAPI } from 'scripts';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { AnnouncementType, PaginationType } from 'types';

// announcement list pages
const AnnouncementListComp: React.FC = () => {
    const [list, setList] = useState<AnnouncementType[]>();
    useEffect(() => {
        RestAPI.get<PaginationType<AnnouncementType>>(APIPath.announcement.index()).then(({ data }) => {
            setList(data.data);
        });
    }, []);
    return (
        <div className={Styles.root}>
            {list?.map((item) => (
                <div key={item.id} className={Styles.card} /* announcement card */>
                    <div className={Styles.cardHeader}>
                        <p className={Styles.cardTitle}>{item.title}</p>
                        <Link to={RoutePath.announcement.edit(item.id)} className={Styles.editButton}>
                            Edit
                        </Link>
                    </div>
                    <p className={Styles.description} dangerouslySetInnerHTML={{ __html: item.body }}></p>
                </div>
            ))}
            {/* list loading status */}
            {!list ? (
                <div className={Styles.loading}>
                    <FaSpinner size={48} className={Styles.loadingIcon} />
                </div>
            ) : null}
            {/* empty state status */}
            {list && list.length === 0 ? <p className={Styles.emptyState}>There is no announcement yet</p> : null}
        </div>
    );
};

export default AnnouncementListComp;
