import { APIPath, RoutePath } from 'data';
import React, { useEffect, useState, useContext } from 'react';
import { FaSpinner } from 'react-icons/fa';
import { Link } from 'react-router-dom';
import { __RestAPI as RestAPI } from 'scripts/api';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { AnnouncementType, PaginationType } from 'types';
import { GlobalContext } from 'context';
import { canUser } from 'context/actions';
import { useTranslation } from 'react-i18next';

const AnnouncementListComp: React.FC = () => {
    const { t } = useTranslation();
    const context = useContext(GlobalContext);
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
                        {canUser('update announcement', context) ? (
                            <Link
                                to={RoutePath.announcement.edit(item.id)}
                                className="text-white bg-ocean-blue hover:bg-ocean-blue focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-ocean-blue dark:hover:bg-ocean-blue focus:outline-none dark:focus:ring-ocean-blue "
                            >
                                {t('Edit')}
                            </Link>
                        ) : (
                            ''
                        )}
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
