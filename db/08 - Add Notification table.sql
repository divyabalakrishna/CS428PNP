/* WARNING: The script below is not multi-run safe!
   Do NOT run more than once. */

USE plannpla_db;

--
-- Table structure for table `notification`
--

CREATE TABLE `Notification` (
  `NotificationID` bigint(20) NOT NULL,
  `UserID` bigint(20) NOT NULL,
  `EventID` bigint(20) NOT NULL,
  `Message` varchar(1000) NOT NULL,
  `Time` datetime NOT NULL,
  `UrlLink` varchar(255) NOT NULL,
  `ImgLink` varchar(255) NOT NULL,
  `Flag` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `notification`
--
ALTER TABLE `Notification`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `UserID` (`UserID`);
  
  --
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `Notification`
  MODIFY `NotificationID` bigint(20) NOT NULL AUTO_INCREMENT;